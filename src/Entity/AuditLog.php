<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;

class AuditLog implements AuditLogInterface
{
    use TimestampableTrait;

    private ?int $id;

    private ?string $content = null;

    private ?string $eventCode = null;

    private AuditLogChannel $channel;

    private AuditLogInvoker $invoker;

    private ?array $context = null;

    public function __construct()
    {
        $this->channel = new AuditLogChannel();
        $this->invoker = new AuditLogInvoker();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getEventCode(): ?string
    {
        return $this->eventCode;
    }

    public function setEventCode(?string $eventCode): void
    {
        $this->eventCode = $eventCode;
    }

    public function getChannel(): AuditLogChannel
    {
        return $this->channel;
    }

    public function setChannel(AuditLogChannel $channel): void
    {
        $this->channel = $channel;
    }

    public function getInvoker(): AuditLogInvoker
    {
        return $this->invoker;
    }

    public function setInvoker(AuditLogInvoker $invoker): void
    {
        $this->invoker = $invoker;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function setContext(?array $context): void
    {
        $this->context = $context;
    }
}
