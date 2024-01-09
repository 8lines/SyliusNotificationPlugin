<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Repository\Sylius;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\CustomerRepository as SyliusCustomerRepository;

final class CustomerRepository extends SyliusCustomerRepository
{
    public function findByPhrase(string $phrase, ?int $limit = null): array
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        return $this->createQueryBuilder('c')
            ->andWhere($expr->orX(
                $expr->like('c.firstName', ':phrase'),
                $expr->like('c.lastName', ':phrase'),
                $expr->like('c.email', ':phrase'),
                $expr->like('c.phoneNumber', ':phrase')
            ))
            ->setParameter('phrase', '%' . $phrase . '%')
            ->orderBy('c.firstName', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
