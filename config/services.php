<?php
declare(strict_types=1);

use EightLines\SyliusNotificationPlugin\Applicator\NotificationVariablesApplicator;
use EightLines\SyliusNotificationPlugin\Applicator\NotificationVariablesApplicatorInterface;
use EightLines\SyliusNotificationPlugin\EventListener\NotificationEventListener;
use EightLines\SyliusNotificationPlugin\Form\Type\NotificationActionCollectionType;
use EightLines\SyliusNotificationPlugin\Form\Type\NotificationActionType;
use EightLines\SyliusNotificationPlugin\Form\Type\NotificationChannelChoiceType;
use EightLines\SyliusNotificationPlugin\Form\Type\NotificationContentTranslationType;
use EightLines\SyliusNotificationPlugin\Form\Type\NotificationContentType;
use EightLines\SyliusNotificationPlugin\Form\Type\NotificationEventChoiceType;
use EightLines\SyliusNotificationPlugin\Form\Type\NotificationType;
use EightLines\SyliusNotificationPlugin\Menu\AdminMenuListener;
use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use EightLines\SyliusNotificationPlugin\NotificationChannel\Symfony\SlackNotificationChannel;
use EightLines\SyliusNotificationPlugin\NotificationEvent\NotificationEventInterface;
use EightLines\SyliusNotificationPlugin\NotificationEvent\Sylius\OrderPaidNotificationEvent;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistry;
use Sylius\Component\Registry\ServiceRegistry;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $configurator->import(__DIR__.'/services/*.php');

    $configurator
        ->parameters()
        ->set('eightlines_sylius_notification_plugin.form.type.notification.validation_groups', 'sylius')
        ->set('eightlines_sylius_notification_plugin.form.type.notification_event.validation_groups', 'sylius')
        ->set('eightlines_sylius_notification_plugin.form.type.notification_content_translation.validation_groups', 'sylius')
    ;

    $services = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services->set(AdminMenuListener::class)
        ->tag('kernel.event_listener', ['event' => 'sylius.menu.admin.main', 'method' => 'addAdminMenuItems']);

    //Form type
    $services->set(NotificationType::class)
        ->tag('form.type')
        ->arg('$dataClass', '%eightlines_sylius_notification_plugin.model.notification.class%')
        ->arg('$validationGroups', '%eightlines_sylius_notification_plugin.form.type.notification.validation_groups%');

    $services->set(NotificationActionType::class)
        ->tag('form.type')
        ->arg('$dataClass', '%eightlines_sylius_notification_plugin.model.notification_action.class%')
        ->arg('$validationGroups', '%eightlines_sylius_notification_plugin.form.type.notification_action.validation_groups%');

    $services->set(NotificationContentType::class)
        ->tag('form.type')
        ->arg('$dataClass', '%eightlines_sylius_notification_plugin.model.notification_content.class%')
        ->arg('$validationGroups', '%eightlines_sylius_notification_plugin.form.type.notification_content.validation_groups%')
        ->arg('$localeProvider', service('sylius.translation_locale_provider.immutable'))
    ;

    $services->set(NotificationContentTranslationType::class)
        ->tag('form.type')
        ->arg('$dataClass', '%eightlines_sylius_notification_plugin.model.notification_content_translation.class%')
        ->arg('$validationGroups', '%eightlines_sylius_notification_plugin.form.type.notification_content_translation.validation_groups%')
    ;

    $services->set(NotificationActionCollectionType::class)
        ->tag('form.type')
        ->arg('$registry', service('eightlines_sylius_notification_plugin.registry.notification_channels'))
    ;

    $services->set(NotificationChannelChoiceType::class)
        ->tag('form.type')
        ->arg('$notificationChannelsRegistry', service('eightlines_sylius_notification_plugin.registry.notification_channels'))
    ;

    $services->set(NotificationEventChoiceType::class)
        ->tag('form.type')
        ->arg('$notificationEventsRegistry', service('eightlines_sylius_notification_plugin.registry.notification_events'))
    ;

    //services
    $services->set('eightlines_sylius_notification_plugin.registry.notification_events', ServiceRegistry::class)
        ->arg('$className', NotificationEventInterface::class)
        ->arg('$context', 'notification event');

    $services->set('eightlines_sylius_notification_plugin.registry.notification_channels', ServiceRegistry::class)
        ->arg('$className', NotificationChannelInterface::class)
        ->arg('$context', 'notification channel');

    $services->instanceof(NotificationEventInterface::class)
        ->tag('eightlines_sylius_notification_plugin.notification_event');

    $services->instanceof(NotificationChannelInterface::class)
        ->tag('eightlines_sylius_notification_plugin.notification_channel');

    // Form Type Registry
    $services->set('eightlines_sylius_notification_plugin.registry.notification_channel.form_types', FormTypeRegistry::class);

    //Event Listeners
    $services->set('eightlines_sylius_notification_plugin.event_listener.notification_event', NotificationEventListener::class)
        ->arg('$messageBus', service('messenger.default_bus'));

    //Notification Events
    $services->set('eightlines_sylius_notification_plugin.notification_event.sylius.order_paid', OrderPaidNotificationEvent::class)
        ->tag('eightlines_sylius_notification_plugin.notification_event');

    //Notification Channels
    $services->set('eightlines_sylius_notification_plugin.notification_channel.slack', SlackNotificationChannel::class)
        ->tag('eightlines_sylius_notification_plugin.notification_channel')
        ->arg('$chatter', service('chatter'));

    //Applicators
    $services->set(NotificationVariablesApplicatorInterface::class, NotificationVariablesApplicator::class);
    $services->alias('eightlines_sylius_notification_plugin.applicator.notification_variables', NotificationVariablesApplicatorInterface::class);
};
