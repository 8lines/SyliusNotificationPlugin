<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

final class NotificationContext
{
    public function __construct(
        private mixed $subject,
        private NotificationEventInterface $event,
        private ?NotificationEventInvoker $syliusInvoker,
    ) {
    }

    public static function create(
        mixed $subject,
        NotificationEventInterface $event,
        ?NotificationEventInvoker $syliusInvoker,
    ): self {
        return new self(
            subject: $subject,
            event: $event,
            syliusInvoker: $syliusInvoker,
        );
    }

    public function getSubject(): mixed
    {
        return $this->subject;
    }

    public function getEvent(): NotificationEventInterface
    {
        return $this->event;
    }

    public function getSyliusInvoker(): ?NotificationEventInvoker
    {
        return $this->syliusInvoker;
    }

    public function getEventName(): string
    {
        return $this->event::getEventName();
    }
}
