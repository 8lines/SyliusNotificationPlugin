<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\DependencyInjection\Compiler;

use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterNotificationChannelsCompilerPass implements CompilerPassInterface
{
    private const NOTIFICATION_CHANNELS_REGISTRY = 'eightlines_sylius_notification_plugin.registry.notification_channels';

    private const NOTIFICATION_CHANNEL_TAG = 'eightlines_sylius_notification_plugin.notification_channel';

    private const CHANNEL_IDENTIFIER_METHOD = 'getIdentifier';
    private const IS_CHANNEL_SUPPORTED_METHOD = 'supports';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(self::NOTIFICATION_CHANNELS_REGISTRY)) {
            return;
        }

        $registry = $container->getDefinition(self::NOTIFICATION_CHANNELS_REGISTRY);

        foreach ($container->findTaggedServiceIds(self::NOTIFICATION_CHANNEL_TAG, true) as $id => $attributes) {
            /** @var array $attribute */
            foreach ($attributes as $attribute) {
                if (!isset($attribute['identifier'])) {
                    $attribute['identifier'] = $this->getIdentifierFromTypeDeclaration($container, $id);
                }

                if (!isset($attribute['supports'])) {
                    $attribute['supports'] = $this->getIsSupportedFromTypeDeclaration($container, $id);
                }

                if (!isset($attribute['supports']) || false === $attribute['supports']) {
                    return;
                }

                $registry->addMethodCall('register', [$attribute['identifier'], new Reference($id)]);
            }
        }
    }

    private function getNotificationChannelClassReflection(
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

        if (false === $reflection->isSubclassOf(NotificationChannelInterface::class)) {
            throw new \InvalidArgumentException(
                sprintf('Service "%s" must implement "%s".', $id, NotificationChannelInterface::class)
            );
        }

        return $reflection;
    }

    private function getIdentifierFromTypeDeclaration(
        ContainerBuilder $container,
        string $id,
    ): string {
        return (string) $this->getNotificationChannelClassReflection($container, $id)
            ->getMethod(self::CHANNEL_IDENTIFIER_METHOD)
            ->invoke(null);
    }

    private function getIsSupportedFromTypeDeclaration(
        ContainerBuilder $container,
        string $id,
    ): ?string {
        return (string) $this->getNotificationChannelClassReflection($container, $id)
            ->getMethod(self::IS_CHANNEL_SUPPORTED_METHOD)
            ->invoke(null);
    }
}
