<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Repository;

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
    public function findNotificationsByEventCode(string $eventCode): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.eventCode = :eventCode')
            ->andWhere('o.enabled = true')
            ->setParameter('eventCode', $eventCode)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return NotificationInterface[]
     */
    public function findNotificationsByEventCodeAndSyliusChannelCode(
        string $eventCode,
        string $channelCode,
    ): array {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.channels', 'c')
            ->andWhere('o.eventCode = :eventCode')
            ->andWhere('c.code = :channelCode')
            ->andWhere('o.enabled = true')
            ->setParameter('eventCode', $eventCode)
            ->setParameter('channelCode', $channelCode)
            ->getQuery()
            ->getResult()
        ;
    }
}
