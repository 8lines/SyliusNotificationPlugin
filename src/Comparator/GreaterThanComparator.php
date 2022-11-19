<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Comparator;

final class GreaterThanComparator implements ComparatorInterface
{
    public function isSatisfiedBy(mixed $subject, mixed $value): bool
    {
        return $subject > $value;
    }
}
