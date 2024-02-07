<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use EightLines\SyliusNotificationPlugin\Entity\NotificationMessage;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('notifyPrimaryRecipient', CheckboxType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.notify_primary_recipient',
                'required' => false,
            ])
            ->add('additionalRecipients', CustomerAutocompleteChoiceType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.additional_recipients',
                'required' => false,
            ])
            ->add('configuration', NotificationConfigurationType::class, [
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
