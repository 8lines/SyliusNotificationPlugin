<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class NotificationConfiguration implements NotificationConfigurationInterface
{
    use TimestampableTrait;

    private ?int $id;

    private bool $notifyPrimaryRecipient = false;

    /**
     * @var ArrayCollection<int, AdminUserInterface>
     */
    private Collection $additionalRecipients;

    private NotificationContentInterface $content;

    private array $custom = [];

    public function __construct()
    {
        $this->additionalRecipients = new ArrayCollection();
        $this->content = new NotificationContent();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isNotifyPrimaryRecipient(): bool
    {
        return $this->notifyPrimaryRecipient;
    }

    public function setNotifyPrimaryRecipient(bool $notifyPrimaryRecipient): void
    {
        $this->notifyPrimaryRecipient = $notifyPrimaryRecipient;
    }

    public function getAdditionalRecipients(): Collection
    {
        return $this->additionalRecipients;
    }

    public function setAdditionalRecipients(Collection $additionalRecipients): void
    {
        $this->additionalRecipients = $additionalRecipients;
    }

    public function getContent(): NotificationContentInterface
    {
        return $this->content;
    }

    public function setContent(NotificationContentInterface $content): void
    {
        $this->content = $content;
    }

    public function getCustom(): array
    {
        return $this->custom;
    }

    public function setCustom(array $custom): void
    {
        $this->custom = $custom;
    }

    public function hasCustomValue(string $key): bool
    {
        return isset($this->custom[$key]);
    }

    public function getCustomValue(string $key): mixed
    {
        return $this->custom[$key] ?? null;
    }

    public function hasAdditionalRecipients(): bool
    {
        return false === $this->additionalRecipients->isEmpty();
    }

    public function hasAnyRecipients(): bool
    {
        return true === $this->isNotifyPrimaryRecipient()
            || true === $this->hasAdditionalRecipients();
    }
}
