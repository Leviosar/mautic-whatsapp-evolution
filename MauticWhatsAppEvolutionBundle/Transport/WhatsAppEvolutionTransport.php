<?php

namespace MauticPlugin\MauticWhatsAppEvolutionBundle\Transport;

use Mautic\LeadBundle\Entity\Lead;
use Mautic\SmsBundle\Sms\TransportInterface;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class WhatsAppEvolutionTransport implements TransportInterface
{
    private $logger;
    private $config;

    public function __construct(LoggerInterface $logger, array $config)
    {
        $this->logger = $logger;
        $this->config = [
            'api_url' => $config['api_url'],
            'api_key' => $config['api_key'],
            'instance_id' => $config['instance_id'],
            'default_from' => $config['default_from'] ?? null
        ];
    }

    public function sendSms(Lead $lead, $content)
    {
        $phone = $lead->getLeadPhoneNumber();
        
        if (empty($phone)) {
            $this->logger->error('WhatsApp Evolution: No phone number found for contact ID '.$lead->getId());
            return false;
        }

        $client = new Client([
            'base_uri' => $this->config['api_url'],
            'timeout' => 15.0,
            'headers' => [
                'apikey' => $this->config['api_key'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        $payload = [
            'number' => $this->formatPhoneNumber($phone),
            'options' => [
                'delay' => 1200,
                'presence' => 'composing'
            ],
            'textMessage' => [
                'text' => $content
            ]
        ];

        if (!empty($this->config['instance_id'])) {
            $payload['instance'] = $this->config['instance_id'];
        }

        try {
            $response = $client->post('/message/sendText', [
                'json' => $payload
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() === 201 && isset($responseData['key']['id'])) {
                $this->logger->info('WhatsApp message sent to '.$phone.' with ID: '.$responseData['key']['id']);
                return true;
            }

            $this->logger->error('WhatsApp Evolution API error: '.$response->getBody());
            return false;

        } catch (\Exception $e) {
            $this->logger->error('WhatsApp Evolution API exception: '.$e->getMessage());
            return false;
        }
    }

    private function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If starts with 0, replace with country code (adjust as needed)
        if (substr($phone, 0, 1) === '0') {
            $phone = '55' . substr($phone, 1); // Brazil as default
        }
        
        return $phone;
    }

    public function getFields(): array
    {
        return ['phone'];
    }
}