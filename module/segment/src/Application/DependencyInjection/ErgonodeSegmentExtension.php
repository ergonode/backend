<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Application\DependencyInjection;

use Ergonode\Segment\Application\DependencyInjection\CompilerPass\SegmentConditionConfiguratorCompilerPass;
use Ergonode\Segment\Application\DependencyInjection\CompilerPass\SegmentGeneratorCompilerPass;
use Ergonode\Segment\Domain\Service\SegmentConfigurationStrategyInterface;
use Ergonode\Segment\Infrastructure\Generator\SegmentGeneratorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 */
class ErgonodeSegmentExtension extends Extension
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
            ->registerForAutoconfiguration(SegmentGeneratorInterface::class)
            ->addTag(SegmentGeneratorCompilerPass::TAG);
        $container
            ->registerForAutoconfiguration(SegmentConfigurationStrategyInterface::class)
            ->addTag(SegmentConditionConfiguratorCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
