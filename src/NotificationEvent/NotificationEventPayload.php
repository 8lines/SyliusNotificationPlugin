<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationEvent;

use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Webmozart\Assert\Assert;

final class NotificationEventPayload
{
    private function __construct(
        private CustomerInterface|AdminUserInterface $syliusTarget,
        private ?ChannelInterface $syliusChannel,
        private ?string $localeCode,
    ) {
    }

    public static function create(
        CustomerInterface|AdminUserInterface $syliusTarget,
        ?ChannelInterface $syliusChannel,
        ?string $localeCode,
    ): self {
        return new self(
            syliusTarget: $syliusTarget,
            syliusChannel: $syliusChannel,
            localeCode: $localeCode,
        );
    }

    public function getSyliusTarget(): CustomerInterface|AdminUserInterface
    {
        return $this->syliusTarget;
    }

    public function getSyliusChannel(): ?ChannelInterface
    {
        return $this->syliusChannel;
    }

    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }
}
