<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\Type;

use EightLines\SyliusNotificationPlugin\Entity\NotificationAction;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class NotificationActionType extends AbstractConfigurableNotificationActionElementType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('configuration', NotificationConfigurationType::class, [
                'label' => false,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $form = $event->getForm();

                /** @var NotificationAction|null $data */
                $data = $event->getData();

                $channelCode = $data?->getChannelCode();
                $channelCodeSelected = null !== $channelCode && '' !== $channelCode;

                $form->add('channelCode', NotificationChannelChoiceType::class, [
                    'label' => 'eightlines_sylius_notification_plugin.ui.notification_channel',
                    'disabled' => true === $channelCodeSelected,
                    'attr' => [
                        'data-form-collection' => 'update',
                    ],
                ]);
            })
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'notification_action';
    }
}
