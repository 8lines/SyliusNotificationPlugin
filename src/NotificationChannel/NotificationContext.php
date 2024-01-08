<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel;

use EightLines\SyliusNotificationPlugin\Entity\NotificationConfigurationInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventVariables;

final class NotificationContext
{
    public function __construct(
        private NotificationEventInterface $event,
        private NotificationChannelInterface $channel,
        private NotificationEventVariables $variables,
        private NotificationConfigurationInterface $configuration,
    ) {
    }

    public static function create(
        NotificationEventInterface $event,
        NotificationChannelInterface $channel,
        NotificationEventVariables $variables,
        NotificationConfigurationInterface $configuration,
    ): self {
        return new self($event, $channel, $variables, $configuration);
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

    public function getEventName(): string
    {
        return $this->event::getEventName();
    }
}
