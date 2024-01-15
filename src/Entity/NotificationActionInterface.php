<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface NotificationActionInterface extends
    ResourceInterface,
    TimestampableInterface
{
    public function getChannelCode(): ?string;

    public function setChannelCode(?string $channelCode): void;

    public function getConfiguration(): NotificationConfigurationInterface;

    public function setConfiguration(NotificationConfigurationInterface $configuration): void;
}
