<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\DependencyInjection;

use Ergonode\Core\Application\DependencyInjection\CompilerPass\RelationshipStrategyInterfaceCompilerPass;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeCoreExtension extends Extension
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
            ->registerForAutoconfiguration(RelationshipStrategyInterface::class)
            ->addTag(RelationshipStrategyInterfaceCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
