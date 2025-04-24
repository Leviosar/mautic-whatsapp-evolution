<?php

use MauticPlugin\MauticWhatsAppEvolutionBundle\Form\Type\ConfigType;
use MauticPlugin\MauticWhatsAppEvolutionBundle\Integration\WhatsAppEvolutionIntegration;

return [
    'name' => 'WhatsApp Evolution',
    'description' => 'Enables WhatsApp messaging via Evolution API 2.x',
    'version' => '1.0',
    'author' => 'João Vitor Maia <maia.tostring@gmail.com',
    
    'services' => [
        'integrations' => [
            'mautic.integration.whatsapp_evolution' => [
                'class' => WhatsAppEvolutionIntegration::class,
                'tags' => [
                    'mautic.integration'
                ]
            ]
        ],
        'forms' => [
            'mautic.form.type.whatsapp_evolution_config' => [
                'class' => ConfigType::class,
                'tags' => ['form.type']
            ]
        ],
        'other' => [
            'mautic.sms.transport.whatsapp_evolution' => [
                'class' => \MauticPlugin\MauticWhatsAppEvolutionBundle\Transport\WhatsAppEvolutionTransport::class,
                'arguments' => [
                    'monolog.logger.mautic',
                    [
                        'api_url' => '%mautic.whatsapp_evolution_api_url%',
                        'api_key' => '%mautic.whatsapp_evolution_api_key%',
                        'instance_id' => '%mautic.whatsapp_evolution_instance_id%',
                        'default_from' => '%mautic.whatsapp_evolution_default_from%'
                    ]
                ],
                'tag' => 'mautic.sms_transport',
                'tagArguments' => [
                    'integrationAlias' => 'WhatsAppEvolution'
                ]
            ],
            'mautic.whatsapp_evolution.event_subscriber' => [
                'class' => \MauticPlugin\MauticWhatsAppEvolutionBundle\Event\WhatsAppSubscriber::class,
                'arguments' => [
                    'mautic.sms.transport.whatsapp_evolution',
                    'mautic.helper.core_parameters'
                ],
                'tags' => ['kernel.event_subscriber']
            ]
        ]
    ],
    
    'parameters' => [
        'whatsapp_evolution_enabled' => false,
        'whatsapp_evolution_api_url' => '',
        'whatsapp_evolution_api_key' => '',
        'whatsapp_evolution_instance_id' => '',
        'whatsapp_evolution_default_from' => ''
    ]
];