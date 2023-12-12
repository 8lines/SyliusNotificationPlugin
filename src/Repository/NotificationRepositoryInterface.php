<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface as SyliusRepositoryInterface;

interface NotificationRepositoryInterface extends SyliusRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;
}
