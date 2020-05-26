<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\DependencyInjection;

use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\CreateExportProfileCommandBuilderCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileFormFactoryCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProcessCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\UpdateExportProfileCommandBuilderCompilerPass;
use Ergonode\Exporter\Application\Provider\CreateExportProfileCommandBuilderInterface;
use Ergonode\Exporter\Application\Provider\ExportProfileFormFactoryInterface;
use Ergonode\Exporter\Application\Provider\UpdateExportProfileCommandBuilderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Exporter\Domain\Entity\Profile\ExportProfileInterface;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileTypeCompilerPass;
use Ergonode\Exporter\Infrastructure\Processor\ExportProcessorInterface;

/**
 */
class ErgonodeExporterExtension extends Extension
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
            ->registerForAutoconfiguration(ExportProfileInterface::class)
            ->addTag(ExportProfileTypeCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ExportProfileFormFactoryInterface::class)
            ->addTag(ExportProfileFormFactoryCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(CreateExportProfileCommandBuilderInterface::class)
            ->addTag(CreateExportProfileCommandBuilderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(UpdateExportProfileCommandBuilderInterface::class)
            ->addTag(UpdateExportProfileCommandBuilderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ExportProcessorInterface::class)
            ->addTag(ExportProcessCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
