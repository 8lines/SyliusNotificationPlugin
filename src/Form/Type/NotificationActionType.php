<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

final class NotificationActionType extends AbstractConfigurableNotificationActionElementType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('channelCode', NotificationChannelChoiceType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.notification_channel',
                'attr' => [
                    'data-form-collection' => 'update',
                ],
            ])
            ->add('configuration', NotificationConfigurationType::class, [
                'label' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'notification_action';
    }
}
