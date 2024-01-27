<?php
declare(strict_types=1);

use EightLines\SyliusNotificationPlugin\Controller\Ajax\NotificationEventVariablesController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services->set(NotificationEventVariablesController::class)
        ->arg('$notificationEventResolver', service('eightlines_sylius_notification_plugin.resolver.notification_event'));
};
