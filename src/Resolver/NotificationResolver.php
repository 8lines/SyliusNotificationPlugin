<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Resolver;

use EightLines\SyliusNotificationPlugin\Entity\NotificationInterface;
use EightLines\SyliusNotificationPlugin\Repository\NotificationRepositoryInterface;

final class NotificationResolver implements NotificationResolverInterface
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    /**
     * @return NotificationInterface[]
     */
    public function resolveByEventCode(string $eventCode): array
    {
        return $this->notificationRepository->findNotificationsByEventCode(
            eventCode: $eventCode,
        );
    }

    /**
     * @return NotificationInterface[]
     */
    public function resolveByEventCodeAndSyliusChannelCode(
        string $eventCode,
        string $channelCode,
    ): array {
        return $this->notificationRepository->findNotificationsByEventCodeAndSyliusChannelCode(
            eventCode: $eventCode,
            channelCode: $channelCode,
        );
    }
}
