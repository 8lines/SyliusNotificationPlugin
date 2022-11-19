<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;

class NotificationAction implements NotificationActionInterface
{
    use TimestampableTrait;

    private ?int $id;

    private ?string $channelCode = null;

    private NotificationConfigurationInterface $configuration;

    public function __construct()
    {
        $this->configuration = new NotificationConfiguration();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }

    public function setChannelCode(?string $channelCode): void
    {
        $this->channelCode = $channelCode;
    }

    public function getConfiguration(): NotificationConfigurationInterface
    {
        return $this->configuration;
    }

    public function setConfiguration(NotificationConfigurationInterface $configuration): void
    {
        $this->configuration = $configuration;
    }
}
