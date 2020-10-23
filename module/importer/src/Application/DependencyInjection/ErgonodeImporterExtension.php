<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\DependencyInjection;

use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceFormFactoryCompilerPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceTypeCompilerPass;
use Ergonode\Importer\Infrastructure\Provider\ImportSourceInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceCompilerPass;
use Ergonode\Importer\Application\Provider\SourceFormFactoryInterface;
use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceImportCompilerPass;
use Ergonode\Importer\Application\Provider\CreateSourceCommandBuilderInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\CreateSourceCommandBuilderCompilerPass;
use Ergonode\Importer\Application\Provider\UpdateSourceCommandBuilderInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\UpdateSourceCommandBuilderCompilerPass;

class ErgonodeImporterExtension extends Extension
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
            ->registerForAutoconfiguration(ImportSourceInterface::class)
            ->addTag(SourceTypeCompilerPass::TAG)
            ->addTag(ServiceCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(SourceFormFactoryInterface::class)
            ->addTag(SourceFormFactoryCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(CreateSourceCommandBuilderInterface::class)
            ->addTag(CreateSourceCommandBuilderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(UpdateSourceCommandBuilderInterface::class)
            ->addTag(UpdateSourceCommandBuilderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(SourceImportProcessorInterface::class)
            ->addTag(ServiceImportCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
