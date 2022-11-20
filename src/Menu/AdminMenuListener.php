<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function buildMenu(MenuBuilderEvent $menuBuilderEvent): void
    {
        $menu = $menuBuilderEvent->getMenu();

        /** @var ItemInterface $marketing */
        $marketing = $menu->getChild('marketing');
        $marketing
            ->addChild('cart_links', [
                'route' => 'cart_links_admin_cart_link_index',
            ])
            ->setLabel('cart_links.ui.cart_links')
            ->setLabelAttribute('icon', 'linkify')
        ;
    }
}
