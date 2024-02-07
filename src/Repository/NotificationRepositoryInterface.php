<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use EightLines\SyliusNotificationPlugin\Entity\NotificationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface as SyliusRepositoryInterface;

interface NotificationRepositoryInterface extends SyliusRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;

    /**
     * @return NotificationInterface[]
     */
    public function findNotificationsByEventCode(string $eventCode): array;

    /**
     * @return NotificationInterface[]
     */
    public function findNotificationsByEventCodeAndSyliusChannelCode(
        string $eventCode,
        string $channelCode,
    ): array;
}
