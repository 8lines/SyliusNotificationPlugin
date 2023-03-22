<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class CartLinkRepository extends EntityRepository implements CartLinkRepositoryInterface
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation', 'WITH', 'translation.locale = :localeCode')
            ->setParameter('localeCode', $localeCode)
        ;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneBySlugAndChannelCode(
        string $slug,
        ?string $localeCode,
        string $channelCode
    ): ?CartLinkInterface
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.translations', 'translation')
            ->innerJoin('o.channels', 'channels')
            ->where('translation.locale = :localeCode')
            ->andWhere('translation.slug = :slug')
            ->andWhere('channels.code = :channelCode')
            ->andWhere('o.enabled = true')
            ->setParameter('localeCode', $localeCode)
            ->setParameter('slug', $slug)
            ->setParameter('channelCode', $channelCode)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
