<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

class NotificationContent implements NotificationContentInterface
{
    use TimestampableTrait;

    use TranslatableTrait {
        __construct as public initializeTranslationsCollection;
    }

    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->getCurrentTranslation()->getSubject();
    }

    public function setSubject(?string $subject): void
    {
        $this->getCurrentTranslation()->setSubject($subject);
    }

    public function getMessage(): ?string
    {
        return $this->getCurrentTranslation()->getMessage();
    }

    public function setMessage(?string $message): void
    {
        $this->getCurrentTranslation()->setMessage($message);
    }

    public function getSubjectByLocaleCode(string $localeCode): ?string
    {
        return $this->getTranslationByLocaleCode($localeCode)->getSubject();
    }

    public function getMessageByLocaleCode(string $localeCode): ?string
    {
        return $this->getTranslationByLocaleCode($localeCode)->getMessage();
    }

    private function getCurrentTranslation(): NotificationContentTranslation
    {
        /** @var NotificationContentTranslation $translation */
        $translation = $this->getTranslation();

        return $translation;
    }

    private function getTranslationByLocaleCode(string $localeCode): NotificationContentTranslation
    {
        $translation = $this->getCurrentTranslation();

        try {
            /** @var NotificationContentTranslation $translation */
            $translation = $this->getTranslation($localeCode);
        } catch (\Exception) {
        }

        return $translation;
    }

    protected function createTranslation(): TranslationInterface
    {
        return new NotificationContentTranslation();
    }
}
