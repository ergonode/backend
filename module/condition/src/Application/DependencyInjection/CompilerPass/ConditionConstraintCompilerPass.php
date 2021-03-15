<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\DependencyInjection\CompilerPass;

use Ergonode\Condition\Infrastructure\Provider\ConditionConstraintProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConditionConstraintCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.condition.condition_set.constraint_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ConditionConstraintProvider::class)) {
            $this->processCalculators($container);
        }
    }

    private function processCalculators(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(ConditionConstraintProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
