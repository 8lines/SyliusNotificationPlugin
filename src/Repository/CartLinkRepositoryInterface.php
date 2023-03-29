<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Repository;

use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface as BaseRepositoryInterface;

interface CartLinkRepositoryInterface extends BaseRepositoryInterface
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder;

    public function findOneAvailableBySlugAndChannelCode(
        string $slug,
        ?string $localeCode,
        string $channelCode
    ): ?CartLinkInterface;
}
