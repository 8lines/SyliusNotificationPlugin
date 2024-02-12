<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel\Symfony;

use EightLines\SyliusNotificationPlugin\Validator\Constraint\TemplateExists;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class MailerNotificationChannelCustomConfigurationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('from', TextType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.email_from',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'eightlines_sylius_notification_plugin.notification.action.custom.email_from.not_blank',
                        'groups' => ['sylius'],
                    ]),
                    new Email([
                        'message' => 'eightlines_sylius_notification_plugin.notification.action.custom.email_from.not_valid',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('template', TextType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.template',
                'required' => false,
                'constraints' => [
                    new TemplateExists(
                        message: 'eightlines_sylius_notification_plugin.notification.action.custom.template.not_exist',
                        groups: ['sylius'],
                    ),
                ],
            ])
        ;
    }
}
