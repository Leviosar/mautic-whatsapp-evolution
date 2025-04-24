<?php

namespace MauticPlugin\MauticWhatsAppEvolutionBundle\Event;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Mautic\SmsBundle\SmsEvents;
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
            SmsEvents::ON_CAMPAIGN_TRIGGER_ACTION => ['onCampaignTriggerAction', 0]
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
                    'eventName' => SmsEvents::ON_CAMPAIGN_TRIGGER_ACTION,
                    'formType' => \Mautic\SmsBundle\Form\Type\SmsSendType::class,
                    'formTypeOptions' => [
                        'update_select' => 'campaignevent_properties_channelId',
                        'with_email_types' => false
                    ],
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

        if ($this->transport->sendSms($lead, $content)) {
            $event->setResult(true);
        } else {
            $event->setFailed('Failed to send WhatsApp message');
        }
    }
}