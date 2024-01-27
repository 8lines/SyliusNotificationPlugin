<?php
declare(strict_types=1);

use EightLines\SyliusNotificationPlugin\Resolver\NotificationChannelResolver;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationChannelResolverInterface;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationEventResolver;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationEventResolverInterface;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationResolver;
use EightLines\SyliusNotificationPlugin\Resolver\NotificationResolverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services->set(NotificationChannelResolverInterface::class, NotificationChannelResolver::class)
        ->arg('$registry', service('eightlines_sylius_notification_plugin.registry.notification_channels'));

    $services->alias('eightlines_sylius_notification_plugin.resolver.notification_channel', NotificationChannelResolverInterface::class);

    $services->set(NotificationEventResolverInterface::class, NotificationEventResolver::class)
        ->arg('$registry', service('eightlines_sylius_notification_plugin.registry.notification_events'));

    $services->set(NotificationEventResolverInterface::class, NotificationEventResolver::class)
        ->arg('$registry', service('eightlines_sylius_notification_plugin.registry.notification_events'));

    $services->alias('eightlines_sylius_notification_plugin.resolver.notification_event', NotificationEventResolverInterface::class);

    $services->set(NotificationResolverInterface::class, NotificationResolver::class)
        ->arg('$notificationRepository', service('eightlines_sylius_notification_plugin.repository.notification'));

    $services->alias('eightlines_sylius_notification_plugin.resolver.notification',  NotificationResolverInterface::class);
};
