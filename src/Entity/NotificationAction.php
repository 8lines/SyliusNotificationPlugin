<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;

class NotificationAction implements NotificationActionInterface
{
    use TimestampableTrait;

    private int $id;

    private ?string $event = null;

    private ?string $type = null;

    private array $configuration = [];

    private ?NotificationMessageInterface $message;

    public function __construct()
    {
        $this->message = new NotificationMessage();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent(?string $event): void
    {
        $this->event = $event;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getMessage(): ?NotificationMessageInterface
    {
        return $this->message;
    }

    public function setMessage(?NotificationMessageInterface $message): void
    {
        $this->message = $message;
    }
}
