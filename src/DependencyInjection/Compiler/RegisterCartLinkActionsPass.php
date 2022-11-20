<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterCartLinkActionsPass implements CompilerPassInterface
{
    private const TAG = 'eightlines_cart_links.cart_link_action';
    private const REGISTRY = 'eightlines_cart_links.registry.cart_link_action';
    private const FORM_REGISTRY = 'eightlines_cart_links.form_registry.cart_link_action';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(self::REGISTRY) || !$container->has(self::FORM_REGISTRY)) {
            return;
        }

        $promotionActionRegistry = $container->getDefinition(self::REGISTRY);
        $promotionActionFormTypeRegistry = $container->getDefinition(self::FORM_REGISTRY);

        $promotionActionTypeToLabelMap = [];
        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['type'], $attribute['label'], $attribute['form_type'])) {
                    throw new \InvalidArgumentException('Tagged promotion action `' . $id . '` needs to have `type`, `form_type` and `label` attributes.');
                }

                $promotionActionTypeToLabelMap[$attribute['type']] = $attribute['label'];
                $promotionActionRegistry->addMethodCall('register', [$attribute['type'], new Reference($id)]);
                $promotionActionFormTypeRegistry->addMethodCall('add', [$attribute['type'], 'default', $attribute['form_type']]);
            }
        }

        $container->setParameter('eightlines_cart_links.cart_link_actions', $promotionActionTypeToLabelMap);
    }
}
