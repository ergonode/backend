<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Application\DependencyInjection;

use Ergonode\Transformer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Transformer\Application\DependencyInjection\CompilerPass\ConverterMapperCompilerPass;
use Ergonode\Transformer\Infrastructure\Converter\Mapper\ConverterMapperInterface;
use Ergonode\Transformer\Application\DependencyInjection\CompilerPass\TransformerGeneratorProviderStrategyCompilerPass;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Application\DependencyInjection\CompilerPass\ConverterCompilerPass;

class ErgonodeTransformerExtension extends Extension
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
            ->registerForAutoconfiguration(ConverterMapperInterface::class)
            ->addTag(ConverterMapperCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(TransformerGeneratorStrategyInterface::class)
            ->addTag(TransformerGeneratorProviderStrategyCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ConverterInterface::class)
            ->addTag(ConverterCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
