<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\DependencyInjection;

use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceFormFactoryCompilerPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceCompilerPass;
use Ergonode\Importer\Infrastructure\Provider\ImportSourceInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceCompilerPass;
use Ergonode\Importer\Application\Provider\SourceFormFactoryInterface;
use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceImportCompilerPass;
use Ergonode\Importer\Application\Provider\CreateSourceCommandBuilderInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\CreateSourceCommandBuilderCompilerPass;
use Ergonode\Importer\Application\Provider\UpdateSourceCommandBuilderInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\UpdateSourceCommandBuilderCompilerPass;
use Ergonode\Importer\Infrastructure\Action\Process\AttributeImportProcessorStrategyInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\AttributeActionProcessorCompilerPass;
use Ergonode\Importer\Infrastructure\Action\ImportActionInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ImportActionCompilerPass;

/**
 */
class ErgonodeImporterExtension extends Extension
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
            ->registerForAutoconfiguration(ImportSourceInterface::class)
            ->addTag(SourceCompilerPass::TAG)
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

        $container
            ->registerForAutoconfiguration(AttributeImportProcessorStrategyInterface::class)
            ->addTag(AttributeActionProcessorCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ImportActionInterface::class)
            ->addTag(ImportActionCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
