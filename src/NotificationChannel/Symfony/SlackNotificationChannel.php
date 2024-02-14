<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel\Symfony;

use EightLines\SyliusNotificationPlugin\Form\Type\NotificationChannel\MessageWithoutRecipientType;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationBody;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

final class SlackNotificationChannel implements NotificationChannelInterface
{
    public function __construct(
        private ChatterInterface $chatter,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(
        NotificationBody $body,
        NotificationContext $context,
    ): void {
        if (false === $body->hasMessage()) {
            return;
        }

        /** @var string $message */
        $message = $body->getMessage();

        $message = new ChatMessage($message);
        $message->transport('slack');

        $this->chatter->send($message);
    }

    public static function getIdentifier(): string
    {
        return 'slack';
    }

    public static function supportsUnknownRecipient(): bool
    {
        return true;
    }

    public static function getConfigurationFormType(): ?string
    {
        return MessageWithoutRecipientType::class;
    }

    public static function supports(): bool
    {
        return true;
    }
}
