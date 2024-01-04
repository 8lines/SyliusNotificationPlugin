<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use EightLines\SyliusNotificationPlugin\Entity\NotificationInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class NotificationRepository extends EntityRepository implements NotificationRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }

    /**
     * @return NotificationInterface[]
     */
    public function findNotificationsByEvent(string $event): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.event = :event')
            ->setParameter('event', $event)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countNotificationsByEventAndChannel(
        string $event,
        string $channel,
    ): int {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->innerJoin('o.channels', 'c')
            ->andWhere('o.event = :event')
            ->andWhere('c.code = :channel')
            ->setParameter('event', $event)
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }
}
