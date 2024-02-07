<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface NotificationMessageInterface extends
    ResourceInterface,
    TimestampableInterface,
    TranslatableInterface
{
    public function getContent(): ?string;

    public function setContent(?string $content): void;

    public function getContentByLocaleCode(string $locale): ?string;
}

