<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin;

use EightLines\SyliusNotificationPlugin\DependencyInjection\Compiler\RegisterNotificationEventsCompilerPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class EightLinesSyliusNotificationPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterNotificationEventsCompilerPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
