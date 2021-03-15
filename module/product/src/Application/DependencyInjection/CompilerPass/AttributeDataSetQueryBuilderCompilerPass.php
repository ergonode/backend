<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\DependencyInjection\CompilerPass;

use Ergonode\Product\Infrastructure\Grid\Builder\DataSetQueryBuilderProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AttributeDataSetQueryBuilderCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.product.dbal_data_set_query_builder_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(DataSetQueryBuilderProvider::class)) {
            $this->processStrategies($container);
        }
    }

    private function processStrategies(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(DataSetQueryBuilderProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
