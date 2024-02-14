<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\DependencyInjection\Compiler;

use EightLines\SyliusNotificationPlugin\NotificationChannel\NotificationChannelInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterNotificationChannelsCompilerPass implements CompilerPassInterface
{
    private const NOTIFICATION_CHANNELS_REGISTRY = 'eightlines_sylius_notification_plugin.registry.notification_channels';
    private const NOTIFICATION_CHANNEL_FORM_TYPES_REGISTRY = 'eightlines_sylius_notification_plugin.registry.notification_channel.configuration_form_types';

    private const NOTIFICATION_CHANNEL_TAG = 'eightlines_sylius_notification_plugin.notification_channel';
    private const NOTIFICATION_CHANNEL_CONFIGURATION_FORM_TYPE_TAG = 'eightlines_sylius_notification_plugin.notification_channel.configuration_form_type';

    private const CHANNEL_IDENTIFIER_METHOD = 'getIdentifier';
    private const IS_CHANNEL_SUPPORTED_METHOD = 'supports';
    private const CONFIGURATION_FORM_TYPE_METHOD = 'getConfigurationFormType';

    /**
     * @throws \Exception
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(self::NOTIFICATION_CHANNELS_REGISTRY)
            || !$container->has(self::NOTIFICATION_CHANNEL_FORM_TYPES_REGISTRY)
        ) {
            return;
        }

        $registry = $container->getDefinition(self::NOTIFICATION_CHANNELS_REGISTRY);
        $formRegistry = $container->getDefinition(self::NOTIFICATION_CHANNEL_FORM_TYPES_REGISTRY);

        foreach ($container->findTaggedServiceIds(self::NOTIFICATION_CHANNEL_TAG, true) as $id => $attributes) {
            /** @var array $attribute */
            foreach ($attributes as $attribute) {
                if (false === isset($attribute['identifier'])) {
                    $attribute['identifier'] = $this->getIdentifierFromTypeDeclaration($container, $id);
                }

                if (false === isset($attribute['supports'])) {
                    $attribute['supports'] = $this->getIsSupportedFromTypeDeclaration($container, $id);
                }

                if (false === isset($attribute['form-type'])) {
                    $attribute['form-type'] = $this->getConfigurationFormTypeFromTypeDeclaration($container, $id);
                }

                if (false === isset($attribute['supports']) || false === $attribute['supports']) {
                    $container->removeDefinition($id);
                    return;
                }

                $registry->addMethodCall('register', [$attribute['identifier'], new Reference($id)]);

                if (true === isset($attribute['form-type'])) {
                    $this->registerNotificationChannelConfigurationFormType(
                        id: $id,
                        container: $container,
                        formRegistry: $formRegistry,
                        attribute: $attribute,
                    );
                }
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
    ): ?bool {
        return (bool) $this->getNotificationChannelClassReflection($container, $id)
            ->getMethod(self::IS_CHANNEL_SUPPORTED_METHOD)
            ->invoke(null);
    }

    private function getConfigurationFormTypeFromTypeDeclaration(
        ContainerBuilder $container,
        string $id,
    ): ?string {
        return (string) $this->getNotificationChannelClassReflection($container, $id)
            ->getMethod(self::CONFIGURATION_FORM_TYPE_METHOD)
            ->invoke(null);
    }

    private function registerNotificationChannelConfigurationFormType(
        string $id,
        ContainerBuilder $container,
        Definition $formRegistry,
        array $attribute,
    ): void {
        $formRegistry->addMethodCall('add', [$attribute['identifier'], 'default', $attribute['form-type']]);

        $container->getDefinition($id)->addTag(self::NOTIFICATION_CHANNEL_CONFIGURATION_FORM_TYPE_TAG, [
            'channel' => $attribute['identifier'],
            'form-type' => $attribute['form-type'],
        ]);
    }
}
