<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Factory;

use EightLines\SyliusNotificationPlugin\Entity\AuditLog;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface as BaseFactoryInterface;

/**
 * @extends BaseFactoryInterface<AuditLog>
 */
interface AuditLogFactoryInterface extends BaseFactoryInterface
{
    public function create(
        string $content,
        string $eventName,
        mixed $context,
        CustomerInterface|AdminUserInterface|null $invoker,
        ?ChannelInterface $channel,
    ): AuditLog;
}
