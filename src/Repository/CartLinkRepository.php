<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Repository;

use Doctrine\ORM\NonUniqueResultException;
use EightLines\SyliusCartLinksPlugin\Entity\CartLinkInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class CartLinkRepository extends EntityRepository implements CartLinkRepositoryInterface
{
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
            ->setParameter('localeCode', $localeCode)
            ->setParameter('slug', $slug)
            ->setParameter('channelCode', $channelCode)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
