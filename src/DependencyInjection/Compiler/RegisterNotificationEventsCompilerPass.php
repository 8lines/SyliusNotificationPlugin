<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\DependencyInjection\Compiler;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterNotificationEventsCompilerPass implements CompilerPassInterface
{
    private const NOTIFICATION_EVENTS_REGISTRY = 'eightlines_sylius_notification_plugin.registry.notification_events';
    private const NOTIFICATION_EVENT_LISTENER = 'eightlines_sylius_notification_plugin.event_listener.notification_event';

    private const NOTIFICATION_EVENT_TAG = 'eightlines_sylius_notification_plugin.notification_event';

    private const EVENT_NAME_METHOD = 'getEventName';


    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(self::NOTIFICATION_EVENTS_REGISTRY)
            || !$container->has(self::NOTIFICATION_EVENT_LISTENER)
        ) {
            return;
        }

        $registry = $container->getDefinition(self::NOTIFICATION_EVENTS_REGISTRY);
        $notificationEvent = $container->getDefinition(self::NOTIFICATION_EVENT_LISTENER);

        foreach ($container->findTaggedServiceIds(self::NOTIFICATION_EVENT_TAG, true) as $id => $attributes) {
            /** @var array $attribute */
            foreach ($attributes as $attribute) {
                if (!isset($attribute['event'])) {
                    $attribute['event'] = $this->getEventNameFromTypeDeclaration($container, $id);
                }

                $registry->addMethodCall('register', [$attribute['event'], new Reference($id)]);

                $notificationEvent->addTag('kernel.event_listener', [
                    'event' => $attribute['event'],
                    'method' => 'onNotificationEvent',
                ]);
            }
        }
    }

    private function getNotificationEventClassReflection(
        ContainerBuilder $container,
        string $id
    ): \ReflectionClass {
        $class = $container->getDefinition($id)->getClass();

        if (null === $class) {
            throw new \InvalidArgumentException(
                sprintf('Cannot get class of service "%s"', $id)
            );
        }

        $reflection = $container->getReflectionClass($class, false);

        if (null === $reflection) {
            throw new \InvalidArgumentException(
                sprintf('Cannot get reflection of service "%s"', $id)
            );
        }

        if (false === $reflection->isSubclassOf(NotificationEventInterface::class)) {
            throw new \InvalidArgumentException(
                sprintf('Service "%s" must implement "%s".', $id, NotificationEventInterface::class)
            );
        }

        return $reflection;
    }

    private function getEventNameFromTypeDeclaration(
        ContainerBuilder $container,
        string $id,
    ): string {
        return (string) $this->getNotificationEventClassReflection($container, $id)
            ->getMethod(self::EVENT_NAME_METHOD)
            ->invoke(null);
    }
}
