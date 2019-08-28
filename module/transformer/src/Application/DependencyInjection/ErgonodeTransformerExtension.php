<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Application\DependencyInjection;

use Ergonode\Transformer\Application\DependencyInjection\CompilerPass\TransformerActionCompilerPass;
use Ergonode\Transformer\Application\DependencyInjection\CompilerPass\TransformerGeneratorStrategyCompilerPass;
use Ergonode\Transformer\Infrastructure\Action\ImportActionInterface;
use Ergonode\Transformer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class ErgonodeImporterExtension
 */
class ErgonodeTransformerExtension extends Extension
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
            ->registerForAutoconfiguration(TransformerGeneratorStrategyInterface::class)
            ->addTag(TransformerGeneratorStrategyCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ImportActionInterface::class)
            ->addTag(TransformerActionCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
