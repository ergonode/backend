<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\DependencyInjection;

use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceFactoryCompilerPass;
use Ergonode\Importer\Domain\Factory\SourceFactoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceCompilerPass;
use Ergonode\Importer\Infrastructure\Provider\ImportSourceInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceCompilerPass;

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
            ->registerForAutoconfiguration(SourceFactoryInterface::class)
            ->addTag(SourceFactoryCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
