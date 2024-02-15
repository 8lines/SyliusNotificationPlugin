<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Resolver;

use EightLines\SyliusNotificationPlugin\Comparator\ComparatorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;

final class ComparatorResolver implements ComparatorResolverInterface
{
    public const EQUAL_TO = 'equal_to';
    public const NOT_EQUAL_TO = 'not_equal_to';
    public const GREATER_THAN = 'greater_than';
    public const GREATER_THAN_OR_EQUAL_TO = 'greater_than_or_equal_to';
    public const LESS_THAN = 'less_than';
    public const LESS_THAN_OR_EQUAL_TO = 'less_than_or_equal_to';

    public function __construct(
        private ServiceLocator $comparatorsLocator,
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function resolve(string $comparatorType): ComparatorInterface
    {
        if (false === $this->comparatorsLocator->has($comparatorType)) {
            throw new \InvalidArgumentException(sprintf('Comparator "%s" not supported', $comparatorType));
        }

        /** @var ComparatorInterface $comparator */
        $comparator = $this->comparatorsLocator->get($comparatorType);
        return $comparator;
    }
}
