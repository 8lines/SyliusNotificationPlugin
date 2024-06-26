<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use EightLines\SyliusNotificationPlugin\Entity\AuditLog;
use Sylius\Component\Resource\Repository\RepositoryInterface as SyliusRepositoryInterface;

/**
 * @extends SyliusRepositoryInterface<AuditLog>
 */
interface AuditLogRepositoryInterface extends SyliusRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;
}
