<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class AuditLogRepository extends EntityRepository implements AuditLogRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }
}
