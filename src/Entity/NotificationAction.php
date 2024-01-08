<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;

class NotificationAction implements NotificationActionInterface
{
    use TimestampableTrait;

    private int $id;

    private ?string $eventCode = null;

    private ?string $channelCode = null;

    private NotificationMessageInterface $message;

    private NotificationConfigurationInterface $configuration;

    public function __construct()
    {
        $this->message = new NotificationMessage();
        $this->configuration = new NotificationConfiguration();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEventCode(): ?string
    {
        return $this->eventCode;
    }

    public function setEventCode(?string $eventCode): void
    {
        $this->eventCode = $eventCode;
    }

    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }

    public function setChannelCode(?string $channelCode): void
    {
        $this->channelCode = $channelCode;
    }

    public function getMessage(): NotificationMessageInterface
    {
        return $this->message;
    }

    public function setMessage(NotificationMessageInterface $message): void
    {
        $this->message = $message;
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
