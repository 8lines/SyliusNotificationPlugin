<?php

declare(strict_types=1);

namespace EightLines\SyliusCartLinksPlugin;

use EightLines\SyliusCartLinksPlugin\DependencyInjection\Compiler\RegisterCartLinkActionsPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class EightLinesSyliusCartLinksPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterCartLinkActionsPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
