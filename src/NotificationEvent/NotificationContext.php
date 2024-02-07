<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

final class NotificationContext
{
    public function __construct(
        private mixed $subject,
        private NotificationEventInterface $event,
    ) {
    }

    public static function create(
        mixed $subject,
        NotificationEventInterface $event,
    ): self
    {
        return new self($subject, $event);
    }

    public function getSubject(): mixed
    {
        return $this->subject;
    }

    public function getEvent(): NotificationEventInterface
    {
        return $this->event;
    }

    public function getEventName(): string
    {
        return $this->event::getEventName();
    }
}
