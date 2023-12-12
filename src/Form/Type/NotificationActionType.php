<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use EightLines\SyliusNotificationPlugin\Entity\NotificationMessage;
use Symfony\Component\Form\FormBuilderInterface;

final class NotificationActionType extends AbstractConfigurableNotificationActionElementType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', NotificationActionChoiceType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.notification_channel',
                'attr' => [
                    'data-form-collection' => 'update',
                ],
            ])
            ->add('message', NotificationMessageType::class, [
                'block_prefix' => 'notification_message',
                'data_class' => NotificationMessage::class,
            ])
        ;
    }
}
