<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel\Symfony;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class MailerNotificationChannelCustomConfigurationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('from', TextType::class, [
                'label' => 'eightlines_sylius_notification_plugin.form.notification_channel.from',
                'required' => true,
            ])
            ->add('template', TextType::class, [
                'label' => 'eightlines_sylius_notification_plugin.form.notification_channel.template',
                'required' => false,
            ])
        ;
    }
}
