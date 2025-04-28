<?php

namespace MauticPlugin\MauticWhatsAppEvolutionBundle\Integration;

use Mautic\IntegrationsBundle\Integration\BasicIntegration;
use Mautic\IntegrationsBundle\Integration\DefaultConfigFormTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\BasicInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormAuthInterface;
use MauticPlugin\MauticWhatsAppEvolutionBundle\Form\Type\ConfigType;

class WhatsAppEvolutionIntegration extends BasicIntegration implements BasicInterface, ConfigFormInterface, ConfigFormAuthInterface
{
    use DefaultConfigFormTrait;

    public const NAME = 'WhatsAppEvolution';

    public const DISPLAY_NAME = 'WhatsApp Evolution API';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getDisplayName(): string
    {
        return self::DISPLAY_NAME;
    }

    public function getIcon(): string
    {
        return 'plugins/MauticWhatsAppEvolutionBundle/Assets/img/evolution-logo.png';
    }

    public function getAuthConfigFormName(): string
    {
        return ConfigType::class;
    }
}