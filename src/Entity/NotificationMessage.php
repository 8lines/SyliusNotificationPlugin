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
        __construct as public initializeTranslationsCollection;
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

    public function getContentByLocaleCode(string $localeCode): ?string
    {
        return $this->getTranslationByLocaleCode($localeCode)->getContent();
    }

    private function getCurrentTranslation(): NotificationMessageTranslation
    {
        /** @var NotificationMessageTranslation $translation */
        $translation = $this->getTranslation();

        return $translation;
    }

    private function getTranslationByLocaleCode(string $localeCode): NotificationMessageTranslation
    {
        $translation = $this->getCurrentTranslation();

        try {
            /** @var NotificationMessageTranslation $translation */
            $translation = $this->getTranslation($localeCode);
        } catch (\Exception $ignored) {
        }

        return $translation;
    }

    protected function createTranslation(): TranslationInterface
    {
        return new NotificationMessageTranslation();
    }
}
