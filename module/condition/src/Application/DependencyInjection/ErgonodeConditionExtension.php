<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\DependencyInjection;

use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ConditionCalculatorCompilerPass;
use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ConditionConfiguratorCompilerPass;
use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ConditionConstraintCompilerPass;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeConditionExtension extends Extension implements PrependExtensionInterface
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
            ->registerForAutoconfiguration(ConditionConfigurationStrategyInterface::class)
            ->addTag(ConditionConfiguratorCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ConditionCalculatorStrategyInterface::class)
            ->addTag(ConditionCalculatorCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ConditionValidatorStrategyInterface::class)
            ->addTag(ConditionConstraintCompilerPass::TAG);

        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        if (!in_array(NelmioApiDocBundle::class, $container->getParameter('kernel.bundles'), true)) {
            return;
        }
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('nelmio_api_doc.yaml');
    }
}
