<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

final class NotificationEventVariableDescription
{
    public function __construct(
        private string $value,
    ) {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
