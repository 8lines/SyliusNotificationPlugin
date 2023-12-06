<?php

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

final class NotificationEventVariableValue
{
    public function __construct(
        private mixed $value,
    ) {
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
