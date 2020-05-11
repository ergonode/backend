<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\DependencyInjection;

use Ergonode\Product\Application\DependencyInjection\CompilerPass\AttributeColumnStrategyStrategyCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\AttributeDataSetQueryBuilderCompilerPass;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductCreateCommandFactoryProviderCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductUpdateCommandFactoryProviderCompilerPass;
use Ergonode\Product\Infrastructure\Factory\Command\CreateProductCommandFactoryInterface;
use Ergonode\Product\Infrastructure\Factory\Command\UpdateProductCommandFactoryInterface;
use Ergonode\Product\Domain\Entity\ProductInterface;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductTypeCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductFormCompilerPass;
use Ergonode\Product\Application\Form\Product\ProductFormInterface;

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
            ->registerForAutoconfiguration(ProductInterface::class)
            ->addTag(ProductTypeCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ProductFormInterface::class)
            ->addTag(ProductFormCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(AttributeColumnStrategyInterface::class)
            ->addTag(AttributeColumnStrategyStrategyCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(AttributeDataSetQueryBuilderInterface::class)
            ->addTag(AttributeDataSetQueryBuilderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(CreateProductCommandFactoryInterface::class)
            ->addTag(ProductCreateCommandFactoryProviderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(UpdateProductCommandFactoryInterface::class)
            ->addTag(ProductUpdateCommandFactoryProviderCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
