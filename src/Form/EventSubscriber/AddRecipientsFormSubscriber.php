<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\EventSubscriber;

use EightLines\SyliusNotificationPlugin\Form\Type\CustomerAutocompleteChoiceType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class AddRecipientsFormSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        $form = $event->getForm();

        $form
            ->add('notifyPrimaryRecipient', CheckboxType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.notify_primary_recipient',
                'required' => false,
            ])
            ->add('additionalRecipients', CustomerAutocompleteChoiceType::class, [
                'label' => 'eightlines_sylius_notification_plugin.ui.additional_recipients',
                'required' => false,
            ])
        ;
    }
}
