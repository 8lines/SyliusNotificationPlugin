<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Controller\Ajax;

use EightLines\SyliusNotificationPlugin\Resolver\NotificationEventResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class NotificationEventVariablesController extends AbstractController
{
    public function __construct(
        private NotificationEventResolverInterface $notificationEventResolver,
    ) {
    }

    public function findAll(Request $request): JsonResponse
    {
        /** @var string|null $eventCode */
        $eventCode = $request->query->get('event');

        $notificationEvent = $this->notificationEventResolver->resolveByEventCode(
            eventCode: $eventCode,
        );

        if (null === $notificationEvent) {
            return $this->json([
                'variables' => [],
            ]);
        }

        return $this->json([
            'variables' => $notificationEvent->getVariableDefinitions(),
        ]);
    }
}
