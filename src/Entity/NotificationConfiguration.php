<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

class NotificationConfiguration implements NotificationConfigurationInterface
{
    private ?array $data = null;

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function getIterator(): \Traversable {
        return new \ArrayIterator($this->data);
    }
}
