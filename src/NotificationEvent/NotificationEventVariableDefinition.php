<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

final class NotificationEventVariableDefinition implements \JsonSerializable
{
    public function __construct(
        private NotificationEventVariableName $name,
        private NotificationEventVariableValue $defaultValue,
        private NotificationEventVariableDescription $description,
    ) {
    }

    public function getName(): NotificationEventVariableName
    {
        return $this->name;
    }

    public function getDefaultValue(): NotificationEventVariableValue
    {
        return $this->defaultValue;
    }

    public function getDescription(): NotificationEventVariableDescription
    {
        return $this->description;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name->value(),
            'defaultValue' => $this->defaultValue->value(),
            'description' => $this->description->value(),
        ];
    }
}
