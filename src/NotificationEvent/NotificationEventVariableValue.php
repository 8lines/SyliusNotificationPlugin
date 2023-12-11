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
}
