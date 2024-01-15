<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface NotificationConfigurationInterface extends
    TimestampableInterface,
    ResourceInterface
{
    public function isNotifyPrimaryRecipient(): bool;

    public function setNotifyPrimaryRecipient(bool $notifyPrimaryRecipient): void;

    /**
     * @return ArrayCollection<int, CustomerInterface>
     */
    public function getAdditionalRecipients(): Collection;

    /**
     * @param ArrayCollection<int, CustomerInterface> $additionalRecipients
     */
    public function setAdditionalRecipients(Collection $additionalRecipients): void;

    public function getContent(): NotificationContentInterface;

    public function setContent(NotificationContentInterface $content): void;

    public function getCustom(): array;

    public function setCustom(array $custom): void;

    public function hasCustomValue(string $key): bool;

    public function getCustomValue(string $key): mixed;

    public function hasAdditionalRecipients(): bool;

    public function hasAnyRecipients(): bool;
}
