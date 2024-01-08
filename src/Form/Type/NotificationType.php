<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

final class NotificationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Notification|null $notification */
        $notification = $builder->getData();
        $codeDisabled = null !== $notification?->getCode();

        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('eventCode', NotificationEventChoiceType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.event',
                'required' => true,
                'disabled' => $codeDisabled,
                'attr' => [
                    'data-type' => 'notification-event-name',
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
            ->add('actions', NotificationActionCollectionType::class, [
                'label' => false,
                'attr' => [
                    'data-type' => 'notification-actions',
                ],
            ])
        ;
    }
}
