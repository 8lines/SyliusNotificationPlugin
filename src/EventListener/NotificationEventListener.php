<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\EventListener;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use EightLines\SyliusNotificationPlugin\Repository\NotificationRepositoryInterface;
use Sylius\Component\Registry\ServiceRegistry;
use Symfony\Component\EventDispatcher\GenericEvent;

class NotificationEventListener
{
    public function __construct(
        private ServiceRegistry $notificationEventsRegistry,
        private ServiceRegistry $notificationChannelsRegistry,
        private NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function onNotificationEvent(
        GenericEvent $event,
        string $eventName,
    ): void {
        if (false === $this->notificationEventsRegistry->has($eventName)) {
            return;
        }

        $notifications = $this->notificationRepository->findNotificationsByEvent($eventName);

        if (0 === \count($notifications)) {
            return;
        }

        /** @var NotificationEventInterface $notificationEvent */
        $notificationEvent = $this->notificationEventsRegistry->get($eventName);

        $channelCode = $notificationEvent
            ->getEventChannel($event->getSubject())
            ?->getCode();

        if (null === $channelCode) {
            return;
        }

        foreach ($notifications as $notification) {
            if (0 === $this->notificationRepository->countNotificationsByEventAndChannel($eventName, $channelCode)) {
                continue;
            }

            foreach ($notification->getActions() as $action) {
                if (null === $action->getType()) {
                    continue;
                }

                /** @var string $notificationChannelCode */
                $notificationChannelCode = $action->getType();

                if (false === $this->notificationChannelsRegistry->has($notificationChannelCode)) {
                    continue;
                }

                /** @var NotificationChannelInterface $notificationChannel */
                $notificationChannel = $this->notificationChannelsRegistry->get($notificationChannelCode);

                $message = $action
                    ->getMessage()
                    ->getContent();

                if (null === $message) {
                    continue;
                }

                $notificationChannel->send($message);
            }
        }
    }
}
