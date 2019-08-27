<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\DependencyInjection;

use Ergonode\Product\Application\DependencyInjection\CompilerPass\AttributeColumnStrategyStrategyCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductFactoryProviderCompilerPass;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class ErgonodeProductExtension
 */
class ErgonodeProductExtension extends Extension
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
            ->registerForAutoconfiguration(AttributeColumnStrategyInterface::class)
            ->addTag(AttributeColumnStrategyStrategyCompilerPass::TAG);


        $container
            ->registerForAutoconfiguration(ProductFactoryInterface::class)
            ->addTag(ProductFactoryProviderCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
