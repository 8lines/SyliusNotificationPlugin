<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

final class NotificationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('eventCode', NotificationEventChoiceType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.event',
                'required' => true,
                'attr' => [
                    'data-type' => 'notification-event-code',
                ]
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
                'required' => false,
            ])
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'sylius.ui.channels',
            ])
            ->add('rules', CollectionType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.rules',
                'entry_type' => NotificationRuleType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('actions', NotificationActionCollectionType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.actions',
            ])
        ;
    }
}
