<?php

namespace EightLines\SyliusNotificationPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterNotificationEventsCompilerPass implements CompilerPassInterface
{
    private const NOTIFICATION_EVENT_TAG = 'eightlines_sylius_notifications_plugin.notification_event';
    private const NOTIFICATION_EVENTS_REGISTRY = 'eightlines_sylius_notifications_plugin.registry.notification_events';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(self::NOTIFICATION_EVENTS_REGISTRY)) {
            return;
        }

        $registry = $container->getDefinition(self::NOTIFICATION_EVENTS_REGISTRY);

        $notificationEvents = [];

        foreach ($container->findTaggedServiceIds(self::NOTIFICATION_EVENT_TAG) as $id => $attributes) {
            dump($attributes);
        }
    }
}
