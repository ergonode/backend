<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Application\DependencyInjection;

use Ergonode\ImporterErgonode\Infrastructure\Factory\Attribute\AttributeFactoryInterface;
use Ergonode\ImporterErgonode\Infrastructure\Factory\Product\ProductCommandFactoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeImporterErgonodeExtension extends Extension
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
}
