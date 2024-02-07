<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Controller\Ajax;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NotificationEventVariablesController extends AbstractController
{
    public function __construct(
        private ServiceRegistryInterface $registry,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var string|null $eventName */
        $eventName = $request->query->get('event');

        if (null === $eventName || false === $this->registry->has($eventName)) {
            return $this->json([
                'variables' => [],
            ]);
        }

        /** @var NotificationEventInterface $event */
        $event = $this->registry->get($eventName);

        return $this->json([
            'variables' => $event->getVariableDefinitions(),
        ]);
    }
}
