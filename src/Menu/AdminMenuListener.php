<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function buildMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $notifications = $menu
            ->addChild('notifications')
            ->setLabel('Notifications')
        ;

        $notifications
            ->addChild('notifications', [
                'route' => 'eightlines_sylius_notification_plugin_admin_notification_index',
            ])
            ->setLabel('eightlines_sylius_notification_plugin.ui.notifications')
            ->setLabelAttribute('icon', 'map signs')
        ;
    }
}
