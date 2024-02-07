<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface AuditLogInterface extends
    ResourceInterface,
    TimestampableInterface
{
    public function getContent(): ?string;

    public function setContent(?string $content): void;

    public function getEventCode(): ?string;

    public function setEventCode(?string $eventCode): void;

    public function getChannel(): AuditLogChannel;

    public function setChannel(AuditLogChannel $channel): void;

    public function getInvoker(): AuditLogInvoker;

    public function setInvoker(AuditLogInvoker $invoker): void;

    public function getContext(): ?array;

    public function setContext(?array $context): void;
}
