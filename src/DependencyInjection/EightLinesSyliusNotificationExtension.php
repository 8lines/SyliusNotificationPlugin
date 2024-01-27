<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class EightLinesSyliusNotificationExtension extends Extension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    /**
     * @psalm-suppress UnusedVariable
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->processConfiguration($this->getConfiguration([], $container), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');
        $loader->load('services.php');
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineMigrations($container);
        $this->prependJmsSerializer($container);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'EightLines\SyliusNotificationPlugin\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@EightLinesSyliusNotificationPlugin/migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return ['Sylius\Bundle\CoreBundle\Migrations'];
    }

    private function prependJmsSerializer(ContainerBuilder $container): void
    {
        foreach ($container->getExtensionConfig('jms_serializer') as $config) {
            if (false === isset($config['metadata'])) {
                continue;
            }

            if (false === isset($config['metadata']['directories'])) {
                continue;
            }

            foreach ($config['metadata']['directories'] as $directoryKey => $directoryConfig) {
                if (strpos($directoryKey, 'custom-sylius') !== 0) {
                    continue;
                }

                $container->prependExtensionConfig('jms_serializer', $config);
            }
        }
    }
}
