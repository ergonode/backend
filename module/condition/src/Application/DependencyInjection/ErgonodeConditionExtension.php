<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\DependencyInjection;

use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ConditionCalculatorCompilerPass;
use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ConditionConfiguratorCompilerPass;
use Ergonode\Condition\Domain\Service\ConditionCalculatorStrategyInterface;
use Ergonode\Condition\Domain\Service\ConfigurationStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 */
class ErgonodeConditionExtension extends Extension
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
            ->registerForAutoconfiguration(ConfigurationStrategyInterface::class)
            ->addTag(ConditionConfiguratorCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ConditionCalculatorStrategyInterface::class)
            ->addTag(ConditionCalculatorCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
