<?php

namespace MauticPlugin\MauticWhatsAppEvolutionBundle\Integration;

use Mautic\IntegrationsBundle\Integration\BasicIntegration;
use Mautic\IntegrationsBundle\Integration\ConfigurationTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\BasicInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormAuthInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormInterface;
use Mautic\IntegrationsBundle\Integration\DefaultConfigFormTrait;
use MauticPlugin\MauticWhatsAppEvolutionBundle\Form\Type\ConfigType;

class WhatsAppEvolutionIntegration extends BasicIntegration implements BasicInterface, ConfigFormInterface, ConfigFormAuthInterface
{
    use DefaultConfigFormTrait;
    use ConfigurationTrait;

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
        return 'fa-whatsapp';
    }

    public function getConfigFormName(): string
    {
        return ConfigType::class;
    }

    public function getAuthConfigFormName(): string
    {
        return ConfigType::class;
    }
}