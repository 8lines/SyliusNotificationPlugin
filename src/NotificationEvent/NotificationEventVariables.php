<?php

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Traversable;

final class NotificationEventVariables implements \IteratorAggregate
{
    public function __construct(
        private array $items,
    ) { }

    public static function create(NotificationEventVariable ...$variables): self
    {
        return new self($variables);
    }

    public function getIterator(): Traversable {
        return new \ArrayIterator($this->items);
    }
}
