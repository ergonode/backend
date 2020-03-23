<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\DependencyInjection;

use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileConstraintCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileFactoryCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileFormFactoryCompilerPass;
use Ergonode\Exporter\Application\Provider\ExportProfileFormFactoryInterface;
use Ergonode\Exporter\Domain\Factory\ExportProfileFactoryInterface;
use Ergonode\Exporter\Infrastructure\ExportProfile\ExportProfileValidatorStrategyInterface;
use Ergonode\Exporter\Infrastructure\Provider\ExportProfileInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

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
            ->addTag(ExportProfileCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ExportProfileFactoryInterface::class)
            ->addTag(ExportProfileFactoryCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ExportProfileValidatorStrategyInterface::class)
            ->addTag(ExportProfileConstraintCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ExportProfileFormFactoryInterface::class)
            ->addTag(ExportProfileFormFactoryCompilerPass::TAG);


        $loader->load('services.yml');
    }
}
