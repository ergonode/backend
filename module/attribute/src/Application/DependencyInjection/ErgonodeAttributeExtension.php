<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\DependencyInjection;

use Ergonode\Attribute\Application\DependencyInjection\CompilerPass\AttributeFactoryInterfaceCompilerPass;
use Ergonode\Attribute\Application\DependencyInjection\CompilerPass\AttributeUpdaterInterfaceCompilerPass;
// phpcs:ignore
use Ergonode\Attribute\Application\DependencyInjection\CompilerPass\AttributeValueConstraintStrategyInterfaceCompilerPass;
use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\Attribute\Domain\AttributeUpdaterInterface;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 */
class ErgonodeAttributeExtension extends Extension
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
            ->registerForAutoconfiguration(AttributeFactoryInterface::class)
            ->addTag(AttributeFactoryInterfaceCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(AttributeUpdaterInterface::class)
            ->addTag(AttributeUpdaterInterfaceCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(AttributeValueConstraintStrategyInterface::class)
            ->addTag(AttributeValueConstraintStrategyInterfaceCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
