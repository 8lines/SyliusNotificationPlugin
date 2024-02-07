<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;

interface NotificationEventInterface
{
    public static function getEventName(): string;

    public function getVariables(NotificationContext $context): NotificationEventVariables;

    public function getVariableDefinitions(): NotificationEventVariableDefinitions;

    public function getPrimaryRecipient(NotificationContext $context): CustomerInterface;

    public function getPrimaryRecipientLocaleCode(NotificationContext $context): ?string;

    public function getSyliusChannel(NotificationContext $context): ?ChannelInterface;
}
