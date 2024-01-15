<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface NotificationContentInterface extends
    ResourceInterface,
    TimestampableInterface,
    TranslatableInterface
{
    public function getSubject(): ?string;

    public function setSubject(?string $subject): void;

    public function getMessage(): ?string;

    public function setMessage(?string $message): void;

    public function getSubjectByLocaleCode(string $localeCode): ?string;

    public function getMessageByLocaleCode(string $localeCode): ?string;
}
