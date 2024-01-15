<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface NotificationContentTranslationInterface extends
    ResourceInterface,
    TranslationInterface
{
    public function getSubject(): ?string;

    public function setSubject(?string $subject): void;

    public function getMessage(): ?string;

    public function setMessage(?string $message): void;
}
