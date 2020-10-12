<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Application\DependencyInjection;

use Ergonode\ImporterErgonode\Application\DependencyInjection\CompilerPass\AttributeFactoryCompilerPass;
use Ergonode\ImporterErgonode\Application\DependencyInjection\CompilerPass\ProductCommandFactoryCompilerPass;
use Ergonode\ImporterErgonode\Infrastructure\Factory\Attribute\AttributeFactoryInterface;
use Ergonode\ImporterErgonode\Infrastructure\Factory\Product\ProductCommandFactoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 */
class ErgonodeImporterErgonodeExtension extends Extension
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
            ->registerForAutoconfiguration(ProductCommandFactoryInterface::class)
            ->addTag(ProductCommandFactoryCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(AttributeFactoryInterface::class)
            ->addTag(AttributeFactoryCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
