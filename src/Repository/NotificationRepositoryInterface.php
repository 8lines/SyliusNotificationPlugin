<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use EightLines\SyliusNotificationPlugin\Entity\NotificationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface as SyliusRepositoryInterface;

interface NotificationRepositoryInterface extends SyliusRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;

    /**
     * @return NotificationInterface[]
     */
    public function findNotificationsByEvent(string $event): array;

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countNotificationsByEventAndChannel(
        string $event,
        string $channel,
    ): int;
}
