<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Comparator;

interface ComparatorInterface
{
    public function isSatisfiedBy(mixed $subject, mixed $value): bool;
}
