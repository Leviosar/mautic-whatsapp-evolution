<?php

namespace MauticPlugin\MauticWhatsAppEvolutionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'message',
            TextareaType::class,
            [
                'label' => 'Message',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your message here ({} for variables)',
                ]
            ]
        );

        $builder->add(
            'Media',
            FileType::class,
            [
                'label' => 'mautic.whatsapp_evolution.media',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Upload media file',
                ],
                'mapped' => false,
            ]
        );
    }
}