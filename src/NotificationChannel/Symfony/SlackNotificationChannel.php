<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\NotificationChannel\Symfony;

use EightLines\SyliusNotificationPlugin\Form\Type\NotificationChannel\SlackNotificationChannelActionType;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationContext;
use Sylius\Component\Core\Model\CustomerInterface;
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
        CustomerInterface $recipient,
        string $message,
        NotificationContext $context,
    ): void {
        $message = $message . ' Sent to channel: ' . $context->getAction()->getConfiguration()->get('channel');

        $message = new ChatMessage($message);
        $message->transport('slack');

        $this->chatter->send($message);
    }

    public static function getIdentifier(): string
    {
        return 'slack';
    }

    public static function getConfigurationFormType(): ?string
    {
        return SlackNotificationChannelActionType::class;
    }

    public static function supports(): bool
    {
        return true;
    }
}
