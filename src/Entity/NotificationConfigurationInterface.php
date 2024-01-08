<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

interface NotificationConfigurationInterface extends \IteratorAggregate
{
    public function getData(): ?array;

    public function setData(?array $data): void;

    public function get(string $key): mixed;
}
