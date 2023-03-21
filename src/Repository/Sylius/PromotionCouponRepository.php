<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Repository\Sylius;

use Sylius\Bundle\PromotionBundle\Doctrine\ORM\PromotionCouponRepository as BasePromotionCouponRepository;

final class PromotionCouponRepository extends BasePromotionCouponRepository
{
    public function findByPhrase(string $phrase, ?int $limit = null): array
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        return $this->createQueryBuilder('o')
            ->leftJoin('o.promotion', 'p')
            ->andWhere($expr->orX(
                'o.code LIKE :phrase',
                'p.code LIKE :phrase',
                'p.name LIKE :phrase'
            ))
            ->setParameter('phrase', '%' . $phrase . '%')
            ->orderBy('p.priority', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
