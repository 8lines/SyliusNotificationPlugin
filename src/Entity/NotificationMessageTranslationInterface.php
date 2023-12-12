<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface NotificationMessageTranslationInterface extends ResourceInterface
{
    public function getContent(): ?string;

    public function setContent(?string $content): void;
}
