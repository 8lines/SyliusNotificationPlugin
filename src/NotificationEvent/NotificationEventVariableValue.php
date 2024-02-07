<?php

declare(strict_types=1);

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

    public function valueAsString(): string
    {
        if (false === is_scalar($this->value)) {
            throw new \InvalidArgumentException('Value is not scalar');
        }

        return (string) $this->value;
    }

    public function isNull(): bool
    {
        return null === $this->value;
    }
}
