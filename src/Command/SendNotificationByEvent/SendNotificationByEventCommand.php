<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Command\SendNotificationByEvent;

final class SendNotificationByEventCommand
{
    public function __construct(
        private string $eventName,
        private mixed $subject,
    ) {
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getSubject(): mixed
    {
        return $this->subject;
    }
}
