<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Traversable;

final class NotificationEventVariables implements \IteratorAggregate
{
    public function __construct(
        private array $items,
    ) { }

    public static function create(NotificationEventVariable ...$items): self
    {
        return new self($items);
    }

    public function getIterator(): Traversable {
        return new \ArrayIterator($this->items);
    }
}
