<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Traversable;

final class NotificationEventVariableDefinitions implements \IteratorAggregate
{
    public function __construct(
        private array $items,
    ) { }

    public static function create(NotificationEventVariableDefinition ...$items): self
    {
        return new self($items);
    }

    public function getIterator(): Traversable {
        return new \ArrayIterator($this->items);
    }
}
