<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Resolver;

use EightLines\SyliusNotificationPlugin\Comparator\ComparatorInterface;

interface ComparatorResolverInterface
{
    public function resolve(string $comparatorType): ComparatorInterface;
}
