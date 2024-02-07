<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class Notification implements NotificationInterface
{
    use TimestampableTrait;

    use ToggleableTrait;

    use ChannelsAwareTrait;

    use NotificationActionsAwareTrait;

    private int $id;

    private ?string $code = null;

    private ?string $eventCode = null;

    public function __construct()
    {
        $this->initializeChannelsCollection();
        $this->initializeActionsCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getEventCode(): ?string
    {
        return $this->eventCode;
    }

    public function setEventCode(?string $eventCode): void
    {
        $this->eventCode = $eventCode;
    }
}
