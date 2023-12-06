<?php

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

interface NotificationEventInterface
{
    public function getEventName(): string;

    public function getVariables(mixed $context): NotificationEventVariables;

    public function getVariableNames(): NotificationEventVariableNames;
}
