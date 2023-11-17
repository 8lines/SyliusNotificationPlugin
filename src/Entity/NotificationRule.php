<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

final class NotificationRule implements NotificationRuleInterface
{
    use TimestampableTrait;

    use ChannelsAwareTrait;

    use ToggleableTrait;

    private int $id;

    private string $code;

    private
}
