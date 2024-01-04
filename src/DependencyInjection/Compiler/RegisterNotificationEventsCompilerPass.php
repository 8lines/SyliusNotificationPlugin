<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\DependencyInjection\Compiler;

use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterNotificationEventsCompilerPass implements CompilerPassInterface
{
    private const NOTIFICATION_EVENTS_REGISTRY = 'eightlines_sylius_notifications_plugin.registry.notification_events';
    private const NOTIFICATION_EVENT_FORM_TYPES_REGISTRY = 'eightlines_sylius_notifications_plugin.registry.notification_event.configuration_form_types';

    private const NOTIFICATION_EVENT_LISTENER = 'eightlines_sylius_notifications_plugin.event_listener.notification_event';

    private const NOTIFICATION_EVENT_TAG = 'eightlines_sylius_notifications_plugin.notification_event';
    private const NOTIFICATION_EVENT_CONFIGURATION_FORM_TYPE_TAG = 'eightlines_sylius_notifications_plugin.notification_event.configuration_form_type';

    private const EVENT_NAME_METHOD = 'getEventName';
    private const CONFIGURATION_FORM_TYPE_METHOD = 'getConfigurationFormType';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(self::NOTIFICATION_EVENTS_REGISTRY)
            || !$container->has(self::NOTIFICATION_EVENT_LISTENER)
        ) {
            return;
        }

        $registry = $container->getDefinition(self::NOTIFICATION_EVENTS_REGISTRY);
        $formRegistry = $container->getDefinition(self::NOTIFICATION_EVENT_FORM_TYPES_REGISTRY);

        $notificationEvent = $container->getDefinition(self::NOTIFICATION_EVENT_LISTENER);

        foreach ($container->findTaggedServiceIds(self::NOTIFICATION_EVENT_TAG, true) as $id => $attributes) {
            /** @var array $attribute */
            foreach ($attributes as $attribute) {
                if (!isset($attribute['event'])) {
                    $attribute['event'] = $this->getEventNameFromTypeDeclaration($container, $id);
                }

                if (!isset($attribute['form-type'])) {
                    $attribute['form-type'] = $this->getConfigurationFormTypeFromTypeDeclaration($container, $id);
                }

                $registry->addMethodCall('register', [$attribute['event'], new Reference($id)]);

                $notificationEvent->addTag('kernel.event_listener', [
                    'event' => $attribute['event'],
                    'method' => 'onNotificationEvent',
                ]);

                if (isset($attribute['form-type'])) {
                    $formRegistry->addMethodCall('add', [$attribute['event'], 'default', $attribute['form-type']]);

                    $notificationEvent->addTag(self::NOTIFICATION_EVENT_CONFIGURATION_FORM_TYPE_TAG, [
                        'event' => $attribute['event'],
                        'form-type' => $attribute['form-type'],
                    ]);
                }
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

    private function getConfigurationFormTypeFromTypeDeclaration(
        ContainerBuilder $container,
        string $id,
    ): ?string {
        return (string)$this->getNotificationEventClassReflection($container, $id)
            ->getMethod(self::CONFIGURATION_FORM_TYPE_METHOD)
            ->invoke(null);
    }
}
