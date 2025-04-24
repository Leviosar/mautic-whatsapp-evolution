<?php

namespace MauticPlugin\MauticWhatsAppEvolutionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'api_url',
            UrlType::class,
            [
                'label' => 'mautic.whatsapp_evolution.api_url',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'https://your-evolution-api-instance.com'
                ]
            ]
        );

        $builder->add(
            'api_key',
            PasswordType::class,
            [
                'label' => 'mautic.whatsapp_evolution.api_key',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your Evolution API Key'
                ]
            ]
        );

        $builder->add(
            'instance_id',
            TextType::class,
            [
                'label' => 'mautic.whatsapp_evolution.instance_id',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your WhatsApp instance ID'
                ]
            ]
        );

        $builder->add(
            'default_from',
            TextType::class,
            [
                'label' => 'mautic.whatsapp_evolution.default_from',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Default sender name'
                ]
            ]
        );
    }
}