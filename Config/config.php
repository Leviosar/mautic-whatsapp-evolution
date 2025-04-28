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
            'whatsapp_evolution.integration' => [
                'class' => WhatsAppEvolutionIntegration::class,
                'tags' => [
                    'mautic.config_integration',
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
            'mautic.whatsapp.transport.evolution' => [
                'class' => \MauticPlugin\MauticWhatsAppEvolutionBundle\Transport\WhatsAppEvolutionTransport::class,
                'arguments' => [
                    'monolog.logger.mautic',
                    [
                        'api_url' => '%mautic.whatsapp_evolution_api_url%',
                        'api_key' => '%mautic.whatsapp_evolution_api_key%',
                        'instance_id' => '%mautic.whatsapp_evolution_instance_id%',
                    ]
                ],
                'tag' => 'mautic.whatsapp_transport',
                'tagArguments' => [
                    'integrationAlias' => 'WhatsAppEvolution',
                    'integrationAliasOverride' => 'whatsapp_evolution',
                    'transportName' => 'WhatsApp Evolution'
                ]
            ],
            'mautic.whatsapp_evolution.event_subscriber' => [
                'class' => \MauticPlugin\MauticWhatsAppEvolutionBundle\Event\WhatsAppSubscriber::class,
                'arguments' => [
                    'mautic.whatsapp.transport.evolution',
                    'mautic.helper.core_parameters'
                ],
                'tags' => ['kernel.event_subscriber']
            ]
        ]
    ],
    
    'parameters' => [
        'whatsapp_evolution_enabled' => true,
        'whatsapp_evolution_api_url' => '',
        'whatsapp_evolution_api_key' => '',
        'whatsapp_evolution_instance_id' => '',
    ]
];