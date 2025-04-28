<?php

namespace MauticPlugin\MauticWhatsAppEvolutionBundle\Transport;

use Mautic\LeadBundle\Entity\Lead;
use GuzzleHttp\Client;
use Mautic\IntegrationsBundle\Helper\IntegrationsHelper;
use MauticPlugin\MauticWhatsAppEvolutionBundle\Integration\WhatsAppEvolutionIntegration;
use Psr\Log\LoggerInterface;

class WhatsAppEvolutionTransport
{
    private $logger;

    private $config;

    public function __construct(
        LoggerInterface $logger,
        IntegrationsHelper $integrationsHelper
    )
    {
        $this->logger = $logger;

        $integration = $integrationsHelper->getIntegration(WhatsAppEvolutionIntegration::NAME);
        
        $keys = $integration->getIntegrationConfiguration()->getApiKeys();

        $this->config = [
            'api_url' => $keys['api_url'],
            'api_key' => $keys['api_key'],
            'instance_id' => $keys['instance_id'],
        ];
    }

    public function send(Lead $lead, $content)
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
            'text' => $content
        ];

        if (!empty($this->config['instance_id'])) {
            $payload['instance'] = $this->config['instance_id'];
        }

        try {
            $response = $client->post('/message/sendText/' . $this->config['instance_id'], [
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