<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel;

use EightLines\SyliusNotificationPlugin\Entity\NotificationActionInterface;
use EightLines\SyliusNotificationPlugin\Entity\NotificationConfigurationInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationContext as EventLevelNotificationContext;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class NotificationContext
{
    public function __construct(
        private NotificationActionInterface $action,
        private NotificationEventInterface $event,
        private NotificationChannelInterface $channel,
        private NotificationEventVariables $variables,
        private NotificationConfigurationInterface $configuration,
        private ChannelInterface $syliusChannel,
        private CustomerInterface|AdminUserInterface $syliusInvoker,
        private EventLevelNotificationContext $eventLevelContext,
    ) {
    }

    public static function create(
        NotificationActionInterface $action,
        NotificationEventInterface $event,
        NotificationChannelInterface $channel,
        NotificationEventVariables $variables,
        NotificationConfigurationInterface $configuration,
        ChannelInterface $syliusChannel,
        CustomerInterface|AdminUserInterface $syliusInvoker,
        EventLevelNotificationContext $eventLevelContext,
    ): self {
        return new self(
            action: $action,
            event: $event,
            channel: $channel,
            variables: $variables,
            configuration: $configuration,
            syliusChannel: $syliusChannel,
            syliusInvoker: $syliusInvoker,
            eventLevelContext: $eventLevelContext,
        );
    }

    public function getAction(): NotificationActionInterface
    {
        return $this->action;
    }

    public function getEvent(): NotificationEventInterface
    {
        return $this->event;
    }

    public function getChannel(): NotificationChannelInterface
    {
        return $this->channel;
    }

    public function getVariables(): NotificationEventVariables
    {
        return $this->variables;
    }

    public function getConfiguration(): NotificationConfigurationInterface
    {
        return $this->configuration;
    }

    public function getSyliusChannel(): ChannelInterface
    {
        return $this->syliusChannel;
    }

    public function getSyliusInvoker(): CustomerInterface|AdminUserInterface
    {
        return $this->syliusInvoker;
    }

    public function getEventLevelContext(): EventLevelNotificationContext
    {
        return $this->eventLevelContext;
    }

    public function getEventName(): string
    {
        return $this->event::getEventName();
    }
}
