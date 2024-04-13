<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Repository\Sylius;

use Sylius\Bundle\UserBundle\Doctrine\ORM\UserRepository as BaseSyliusUserRepository;

final class AdminUserRepository extends BaseSyliusUserRepository
{
    public function findByPhrase(string $phrase, ?int $limit = null): array
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        return $this->createQueryBuilder('u')
            ->andWhere($expr->orX(
                $expr->like('u.firstName', ':phrase'),
                $expr->like('u.lastName', ':phrase'),
                $expr->like('u.email', ':phrase')
            ))
            ->setParameter('phrase', '%' . $phrase . '%')
            ->orderBy('u.username', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
