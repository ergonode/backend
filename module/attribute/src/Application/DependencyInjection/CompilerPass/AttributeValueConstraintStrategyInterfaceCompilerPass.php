<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\DependencyInjection\CompilerPass;

use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AttributeValueConstraintStrategyInterfaceCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.attribute.attribute_validation_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(AttributeValueConstraintProvider::class)) {
            $this->processStrategies($container);
        }
    }

    private function processStrategies(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(AttributeValueConstraintProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
