<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use EightLines\SyliusNotificationPlugin\Entity\NotificationMessage;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

final class NotificationActionType extends AbstractConfigurableNotificationActionElementType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('event', HiddenType::class, [
                'attr' => [
                    'data-type' => 'notification-action-event',
                    'data-form-collection' => 'update',
                ],
            ])
            ->add('type', NotificationChannelChoiceType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.notification_channel',
            ])
            ->add('configuration', null, [
                'label' => false,
            ])
            ->add('message', NotificationMessageType::class, [
                'block_prefix' => 'notification_message',
                'data_class' => NotificationMessage::class,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'notification_action';
    }
}
