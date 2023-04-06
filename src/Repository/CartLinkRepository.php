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
    public function findOneAvailableBySlugAndChannelCode(
        string $slug,
        ?string $localeCode,
        string $channelCode
    ): ?CartLinkInterface
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        return $this->createQueryBuilder('o')
            ->leftJoin('o.translations', 'translation')
            ->innerJoin('o.channels', 'channels')
            ->where($expr->andX(
                'o.enabled = true',
                'translation.slug = :slug',
                'channels.code = :channelCode',
                'translation.locale = :localeCode',
                $expr->orX('o.usageLimit IS NULL', 'o.usageLimit > o.used'),
                $expr->orX('o.startsAt IS NULL', 'o.startsAt <= CURRENT_TIMESTAMP()'),
                $expr->orX('o.endsAt IS NULL', 'o.endsAt > CURRENT_TIMESTAMP()'),
            ))
            ->setParameter('localeCode', $localeCode)
            ->setParameter('slug', $slug)
            ->setParameter('channelCode', $channelCode)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
