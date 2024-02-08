<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Sylius\Component\Core\Model\ChannelInterface;

interface NotificationEventInterface
{
    public static function getEventName(): string;

    public function getEventPayload(NotificationContext $context): NotificationEventPayload;

    public function getVariables(NotificationContext $context): NotificationEventVariables;

    public function getVariableDefinitions(): NotificationEventVariableDefinitions;
}
