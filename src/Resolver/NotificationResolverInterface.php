<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Resolver;

use EightLines\SyliusNotificationPlugin\Entity\NotificationInterface;

interface NotificationResolverInterface
{
    /**
     * @return NotificationInterface[]
     */
    public function resolveByEventCode(string $eventCode): array;

    /**
     * @return NotificationInterface[]
     */
    public function resolveByEventCodeAndSyliusChannelCode(
        string $eventCode,
        string $channelCode,
    ): array;
}
