<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Repository\Sylius;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\PromotionRepository as BasePromotionRepository;

final class PromotionRepository extends BasePromotionRepository
{
    public function findByPhrase(string $phrase, ?int $limit = null): array
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        return $this->createQueryBuilder('o')
            ->andWhere($expr->orX(
                'o.name LIKE :phrase',
                'o.code LIKE :phrase',
            ))
            ->setParameter('phrase', '%' . $phrase . '%')
            ->orderBy('o.priority', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
