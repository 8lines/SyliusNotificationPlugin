<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel\Symfony;

use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

class SlackNotificationChannel implements NotificationChannelInterface
{
    public function __construct(
        private ChatterInterface $chatter,
    ) {
    }

    public function send(
        string $message,
    ): void {

        $message = new ChatMessage($message);
        $message->transport('slack');

        $this->chatter->send($message);
    }

    public static function getIdentifier(): string
    {
        return 'slack';
    }

    public static function supports(): bool
    {
        return true;
    }
}
