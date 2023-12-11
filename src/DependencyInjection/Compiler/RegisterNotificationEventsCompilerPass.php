<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterNotificationEventsCompilerPass implements CompilerPassInterface
{
    private const NOTIFICATION_EVENTS_REGISTRY = 'eightlines_sylius_notifications_plugin.registry.notification_events';
    private const NOTIFICATION_EVENT_LISTENER = 'eightlines_sylius_notifications_plugin.event_listener.notification_event';

    private const NOTIFICATION_EVENT_TAG = 'eightlines_sylius_notifications_plugin.notification_event';

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

    private function getEventNameFromTypeDeclaration(
        ContainerBuilder $container,
        string $id
    ): string {
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

        if (!$reflection->hasMethod(self::EVENT_NAME_METHOD)) {
            throw new \InvalidArgumentException(
                sprintf('Service "%s" must have a "%s" method.', $id, self::EVENT_NAME_METHOD)
            );
        }

        $method = $reflection->getMethod(self::EVENT_NAME_METHOD);

        if (!$method->isStatic()) {
            throw new \InvalidArgumentException(
                sprintf('Method "%s" of service "%s" must be static.', self::EVENT_NAME_METHOD, $id)
            );
        }

        if ($method->getNumberOfRequiredParameters() > 0) {
            throw new \InvalidArgumentException(
                sprintf('Method "%s" of service "%s" must not have required arguments.', self::EVENT_NAME_METHOD, $id)
            );
        }

        $returnType = $method->getReturnType();

        if (!$returnType instanceof \ReflectionNamedType) {
            throw new \InvalidArgumentException(
                sprintf('Method "%s" of service "%s" must have a return type.', self::EVENT_NAME_METHOD, $id)
            );
        }

        if ($returnType->getName() !== 'string') {
            throw new \InvalidArgumentException(sprintf('Method "%s" of service "%s" must return a string.', self::EVENT_NAME_METHOD, $id));
        }

        return (string) $method->invoke(null);;
    }
}
