<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

interface NotificationEventInterface
{
    public static function getEventName(): string;

    public static function getConfigurationFormType(): ?string;

    public function getVariables(object $subject): NotificationEventVariables;

    public function getVariableDefinitions(): NotificationEventVariableDefinitions;
}
