<?php

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

final class NotificationEventVariableName
{
    public function __construct(
        private string $value,
    ) {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function same(NotificationEventVariableName $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function __equals(NotificationEventVariableName $other): bool
    {
        return $this->value === $other->value;
    }

    public function __compareTo(NotificationEventVariableName $other): int
    {

    }

    public function __another(NotificationEventVariableName $other): int
    {

    }
}
