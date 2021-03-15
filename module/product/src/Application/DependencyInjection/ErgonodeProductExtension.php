<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\DependencyInjection;

use Ergonode\BatchAction\Infrastructure\Provider\BatchActionFilterIdsInterface;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\AttributeColumnStrategyStrategyCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\AttributeDataSetQueryBuilderCompilerPass;
use Ergonode\Product\Application\Form\Product\ProductFormInterface;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;
use Ergonode\Product\Infrastructure\Strategy\ProductFactoryStrategyInterface;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductCreateCommandFactoryProviderCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductUpdateCommandFactoryProviderCompilerPass;
use Ergonode\Product\Infrastructure\Factory\Command\CreateProductCommandFactoryInterface;
use Ergonode\Product\Infrastructure\Factory\Command\UpdateProductCommandFactoryInterface;
use Ergonode\Product\Domain\Entity\ProductInterface;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductTypeCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductFormCompilerPass;

/**
 * Class ErgonodeProductExtension
 */
class ErgonodeProductExtension extends Extension implements PrependExtensionInterface
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

        $container
            ->registerForAutoconfiguration(ProductFactoryStrategyInterface::class)
            ->addTag('component.product.product_factory_strategy');

        $container
            ->registerForAutoconfiguration(BatchActionFilterIdsInterface::class)
            ->addTag('batch_action.filter_provider.interface');

        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        if (!in_array(NelmioApiDocBundle::class, $container->getParameter('kernel.bundles'), true)) {
            return;
        }
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('nelmio_api_doc.yaml');
    }
}
