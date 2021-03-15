<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\DependencyInjection\CompilerPass;

use Ergonode\Product\Infrastructure\Grid\Column\Provider\AttributeColumnProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AttributeColumnStrategyStrategyCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.product.attribute_column_strategy_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(AttributeColumnProvider::class)) {
            $this->processStrategies($container);
        }
    }

    private function processStrategies(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(AttributeColumnProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
