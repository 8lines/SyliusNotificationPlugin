<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Form\EventSubscriber;

use EightLines\SyliusNotificationPlugin\Entity\NotificationContent;
use EightLines\SyliusNotificationPlugin\Form\Type\NotificationContentType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class AddContentFormSubscriber implements EventSubscriberInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        private bool $subject = true,
        private bool $message = true,
        private array $options = [],
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        $form = $event->getForm();

        $form->add(
            child: 'content',
            type: NotificationContentType::class,
            options: [
                'block_prefix' => 'notification_content',
                'data_class' => NotificationContent::class,
                'subject' => $this->subject,
                'message' => $this->message,
                'entry_options' => $this->options,
            ]
        );
    }
}
