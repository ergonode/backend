<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\DependencyInjection;

use Ergonode\Channel\Application\DependencyInjection\CompilerPass\ChannelGeneratorCompilerPass;
use Ergonode\Channel\Infrastructure\Generator\ChannelGeneratorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 */
class ErgonodeChannelExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../Resources/config')
        );

        $container
            ->registerForAutoconfiguration(ChannelGeneratorInterface::class)
            ->addTag(ChannelGeneratorCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
