<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

class NotificationMessage implements NotificationMessageInterface
{
    use TimestampableTrait;

    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    private int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->getCurrentTranslation()->getContent();
    }

    public function setContent(?string $content): void
    {
        $this->getCurrentTranslation()->setContent($content);
    }

    private function getCurrentTranslation(): NotificationMessageTranslation
    {
        /** @var NotificationMessageTranslation $translation */
        $translation = $this->getTranslation();

        return $translation;
    }

    protected function createTranslation(): TranslationInterface
    {
        return new NotificationMessageTranslation();
    }
}
