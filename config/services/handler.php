<?php
declare(strict_types=1);

use EightLines\SyliusNotificationPlugin\Command\RunNotificationAction;
use EightLines\SyliusNotificationPlugin\Command\RunNotificationActionHandler;
use EightLines\SyliusNotificationPlugin\Command\SendNotificationByEvent;
use EightLines\SyliusNotificationPlugin\Command\SendNotificationByEventHandler;
use EightLines\SyliusNotificationPlugin\Command\SendNotificationToRecipient;
use EightLines\SyliusNotificationPlugin\Command\SendNotificationToRecipientHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services->set(SendNotificationByEventHandler::class)
        ->arg('$notificationResolver', service('eightlines_sylius_notification_plugin.resolver.notification'))
        ->arg('$notificationEventResolver', service('eightlines_sylius_notification_plugin.resolver.notification_event'))
        ->arg('$messageBus', service('messenger.default_bus'))
        ->tag('messenger.message_handler', ['handles' => SendNotificationByEvent::class])
    ;

    $services->set(RunNotificationActionHandler::class)
        ->arg('$notificationChannelResolver', service('eightlines_sylius_notification_plugin.resolver.notification_channel'))
        ->arg('$commandBus', service('messenger.default_bus'))
        ->tag('messenger.message_handler', ['handles' => RunNotificationAction::class])
    ;

    $services->set(SendNotificationToRecipientHandler::class)
        ->arg('$notificationVariablesApplicator', service('eightlines_sylius_notification_plugin.applicator.notification_variables'))
        ->tag('messenger.message_handler', ['handles' => SendNotificationToRecipient::class])
    ;
};
