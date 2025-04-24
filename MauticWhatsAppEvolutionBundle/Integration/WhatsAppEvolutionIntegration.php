<?php

namespace MauticPlugin\MauticWhatsAppEvolutionBundle\Integration;

use Mautic\IntegrationsBundle\Integration\BasicIntegration;
use Mautic\IntegrationsBundle\Integration\Interfaces\BasicInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormInterface;
use Mautic\IntegrationsBundle\Integration\DefaultConfigFormTrait;

class WhatsAppEvolutionIntegration extends BasicIntegration implements BasicInterface, ConfigFormInterface
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
        return 'fa-whatsapp';
    }
}