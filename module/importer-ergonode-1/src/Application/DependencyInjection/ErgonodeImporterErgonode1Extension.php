<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Application\DependencyInjection;

use Ergonode\ImporterErgonode1\Infrastructure\Factory\Attribute\AttributeFactoryInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Factory\Product\ProductCommandFactoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeImporterErgonode1Extension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../Resources/config')
        );

        $container
            ->registerForAutoconfiguration(ProductCommandFactoryInterface::class)
            ->addTag('component.ergonode-importer.product_command_factory_interface');

        $container
            ->registerForAutoconfiguration(AttributeFactoryInterface::class)
            ->addTag('component.ergonode-importer.attribute_factory_interface');

        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $this->prependFlysystem($container);
    }

    private function prependFlysystem(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('flysystem.yaml');
    }
}
