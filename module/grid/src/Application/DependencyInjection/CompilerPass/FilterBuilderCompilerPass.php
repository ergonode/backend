<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Application\DependencyInjection\CompilerPass;

use Ergonode\Grid\Filter\FilterBuilderProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FilterBuilderCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.grid.builder.filter';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(FilterBuilderProvider::class)) {
            $this->processStrategies($container);
        }
    }

    private function processStrategies(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(FilterBuilderProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
