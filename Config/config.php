<?php

use MauticPlugin\MauticWhatsAppEvolutionBundle\Integration\WhatsAppEvolutionIntegration;

return [
    'name' => 'WhatsApp Evolution',
    'description' => 'Enables WhatsApp messaging via Evolution API 2.x',
    'version' => '1.0.2',
    'author' => 'João Vitor Maia <maia.tostring@gmail.com',
    'parameters' => [
        'whatsapp_evolution_enabled' => true,
        'whatsapp_evolution_api_url' => '',
        'whatsapp_evolution_api_key' => '',
        'whatsapp_evolution_instance_id' => '',
    ],
    'services' => [
        'integrations' => [
            'mautic.integration.whatsapp' => [
                'class' => WhatsAppEvolutionIntegration::class,
                'tags' => [
                    'mautic.integration',
                    'mautic.basic_integration',
                    'mautic.config_integration',
                    'mautic.auth_integration',
                ],
            ],
        ],
    ]
];