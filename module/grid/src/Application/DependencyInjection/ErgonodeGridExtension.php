<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Application\DependencyInjection;

use Ergonode\Grid\Application\DependencyInjection\CompilerPass\ColumnRendererCompilerPass;
use Ergonode\Grid\Column\Renderer\ColumnRendererInterface;
use Ergonode\Grid\Filter\Builder\FilterBuilderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeGridExtension extends Extension
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
            ->registerForAutoconfiguration(ColumnRendererInterface::class)
            ->addTag(ColumnRendererCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(FilterBuilderInterface::class)
            ->addTag('grid.filter_builder_provider.interface');

        $loader->load('services.yaml');
    }
}
