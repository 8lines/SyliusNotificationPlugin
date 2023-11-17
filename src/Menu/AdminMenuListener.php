<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Menu;

use JetBrains\PhpStorm\NoReturn;
use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function buildMenu(MenuBuilderEvent $menuBuilderEvent): void
    {
        $menu = $menuBuilderEvent->getMenu();

        /** @var ItemInterface $notifications */
        $notifications = $menu
            ->addChild('notifications')
            ->setLabel('Notifications')
        ;
        $notifications
            ->addChild('notification_messages', [
                'route' => 'notification_admin_message_index',
            ])
            ->setLabel('Messages')
            ->setLabelAttribute('icon', 'paper plane')
        ;
        $notifications
            ->addChild('notification_rules', [
                'route' => 'notification_admin_rule_index',
            ])
            ->setLabel('Rules')
            ->setLabelAttribute('icon', 'map signs')
        ;
    }
}
