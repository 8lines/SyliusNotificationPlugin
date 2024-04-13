<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command\SendNotificationByEvent;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInvoker;

final class SendNotificationByEventCommand
{
    private function __construct(
        private string $eventName,
        private mixed $subject,
        private ?array $invoker,
    ) {
    }

    public static function create(
        string $eventName,
        mixed $subject,
        ?NotificationEventInvoker $invoker,
    ): self {
        return new self(
            eventName: $eventName,
            subject: $subject,
            invoker: $invoker?->toArray(),
        );
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getSubject(): mixed
    {
        return $this->subject;
    }

    public function getInvoker(): ?NotificationEventInvoker
    {
        if (null === $this->invoker) {
            return null;
        }

        return NotificationEventInvoker::fromArray($this->invoker);
    }
}
