<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface NotificationActionInterface extends
    ResourceInterface,
    TimestampableInterface
{
    public function getEventCode(): ?string;

    public function setEventCode(?string $eventCode): void;

    public function getChannelCode(): ?string;

    public function setChannelCode(?string $channelCode): void;

    public function isNotifyPrimaryRecipient(): bool;

    public function setNotifyPrimaryRecipient(bool $notifyPrimaryRecipient): void;

    public function getAdditionalRecipients(): Collection;

    public function setAdditionalRecipients(Collection $additionalRecipients): void;

    public function getMessage(): NotificationMessageInterface;

    public function setMessage(NotificationMessageInterface $message): void;

    public function getConfiguration(): NotificationConfigurationInterface;

    public function setConfiguration(NotificationConfigurationInterface $configuration): void;

    public function hasAnyRecipients(): bool;
}
