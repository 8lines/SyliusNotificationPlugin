<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Traversable;

final class NotificationEventVariableDefinitions implements \IteratorAggregate
{
    public function __construct(
        private array $items,
    ) {
    }

    public static function create(NotificationEventVariableDefinition ...$items): self
    {
        return new self($items);
    }

    public function getByName(NotificationEventVariableName $name): NotificationEventVariableDefinition
    {
        /** @var NotificationEventVariableDefinition $item */
        foreach ($this->items as $item) {
            if (false === $item->getName()->isEqual($name)) {
                continue;
            }
            return $item;
        }

        throw new \InvalidArgumentException(sprintf('Variable with name "%s" not found.', $name->value()));
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
