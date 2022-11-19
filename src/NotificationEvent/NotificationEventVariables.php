<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Traversable;

final class NotificationEventVariables implements \IteratorAggregate
{
    /**
     * @param array<NotificationEventVariable> $items
     */
    public function __construct(
        private array $items,
    ) {
    }

    public static function create(NotificationEventVariable ...$items): self
    {
        return new self($items);
    }

    public function getByName(string $name): ?NotificationEventVariable
    {
        return array_filter(
            array: $this->items,
            callback: fn (NotificationEventVariable $item): bool => $item->getName()->value() === $name,
        )[0] ?? null;
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
