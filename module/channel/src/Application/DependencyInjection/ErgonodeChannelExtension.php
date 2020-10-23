<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryInterface;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\ChannelFormFactoryCompilerPass;
use Ergonode\Channel\Domain\Entity\ChannelInterface;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\ChannelTypeCompilerPass;
use Ergonode\Channel\Application\Provider\CreateChannelCommandBuilderInterface;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\CreateChannelCommandBuilderCompilerPass;
use Ergonode\Channel\Application\Provider\UpdateChannelCommandBuilderInterface;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\UpdateChannelCommandBuilderCompilerPass;

class ErgonodeChannelExtension extends Extension
{
    /**
     * @param array $configs
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
            ->registerForAutoconfiguration(ChannelFormFactoryInterface::class)
            ->addTag(ChannelFormFactoryCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ChannelInterface::class)
            ->addTag(ChannelTypeCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(CreateChannelCommandBuilderInterface::class)
            ->addTag(CreateChannelCommandBuilderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(UpdateChannelCommandBuilderInterface::class)
            ->addTag(UpdateChannelCommandBuilderCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
