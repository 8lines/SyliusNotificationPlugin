<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

final class NotificationEventVariable
{
    public function __construct(
        private NotificationEventVariableName $name,
        private NotificationEventVariableValue $value,
    ) {
    }

    public function getName(): NotificationEventVariableName
    {
        return $this->name;
    }

    public function getValue(): NotificationEventVariableValue
    {
        return $this->value;
    }
}
