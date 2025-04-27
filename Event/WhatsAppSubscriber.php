<?php

namespace MauticPlugin\MauticWhatsAppEvolutionBundle\Event;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Mautic\CoreBundle\Helper\CoreParametersHelper;
use MauticPlugin\MauticWhatsAppEvolutionBundle\Form\Type\MessageType;
use MauticPlugin\MauticWhatsAppEvolutionBundle\MauticWhatsappEvolutionEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use MauticPlugin\MauticWhatsAppEvolutionBundle\Transport\WhatsAppEvolutionTransport;

class WhatsAppSubscriber implements EventSubscriberInterface
{
    private $transport;
    private $coreParameters;

    public function __construct(WhatsAppEvolutionTransport $transport, CoreParametersHelper $coreParameters)
    {
        $this->transport = $transport;
        $this->coreParameters = $coreParameters;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CampaignEvents::CAMPAIGN_ON_BUILD => ['onCampaignBuild', 0],
            MauticWhatsappEvolutionEvents::ON_CAMPAIGN_TRIGGER_ACTION => ['onCampaignTriggerAction', 0]
        ];
    }

    public function onCampaignBuild(CampaignBuilderEvent $event): void
    {
        if ($this->coreParameters->get('whatsapp_evolution_enabled')) {
            $event->addAction(
                'whatsapp.send_message',
                [
                    'label' => 'Send WhatsApp message',
                    'description' => 'Send a WhatsApp message to contacts via Evolution API',
                    'eventName' => MauticWhatsappEvolutionEvents::ON_CAMPAIGN_TRIGGER_ACTION,
                    'formType' => MessageType::class,
                    'formTypeOptions' => [],
                    'channel' => 'whatsapp',
                    'channelIdField' => 'channelId'
                ]
            );
        }
    }

    public function onCampaignTriggerAction(CampaignExecutionEvent $event): void
    {
        $lead = $event->getLead();
        $content = $event->getConfig()['message'];

        if ($this->transport->send($lead, $content)) {
            $event->setResult(true);
        } else {
            $event->setFailed('Failed to send WhatsApp message');
        }
    }
}