<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Application\DependencyInjection;

use Ergonode\ImporterErgonode1\Infrastructure\Factory\Attribute\ImportAttributeCommandFactoryInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Factory\Product\ProductCommandFactoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeImporterErgonode1Extension extends Extension
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
            ->registerForAutoconfiguration(ImportAttributeCommandFactoryInterface::class)
            ->addTag('component.ergonode-importer.attribute_command_factory');

        $loader->load('services.yml');
    }
}
