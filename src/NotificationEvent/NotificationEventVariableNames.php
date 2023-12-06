<?php

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Traversable;

final class NotificationEventVariableNames implements \IteratorAggregate
{
    public function __construct(
        private array $items,
    ) { }

    public static function create(NotificationEventVariableName ...$items): self
    {
        return new self($items);
    }

    public function getIterator(): Traversable {
        return new \ArrayIterator($this->items);
    }
}
