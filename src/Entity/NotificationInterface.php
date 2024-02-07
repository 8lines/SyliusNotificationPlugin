<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

interface NotificationInterface extends
    ResourceInterface,
    CodeAwareInterface,
    ToggleableInterface,
    TimestampableInterface,
    ChannelsAwareInterface,
    NotificationActionsAwareInterface
{
    public function getEventCode(): ?string;

    public function setEventCode(?string $eventCode): void;
}
