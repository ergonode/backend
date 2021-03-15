<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\DependencyInjection\CompilerPass;

use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RelationshipStrategyInterfaceCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.core.relationship_strategy_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(RelationshipsResolverInterface::class)) {
            $this->processStrategies($container);
        }
    }

    private function processStrategies(ContainerBuilder $container): void
    {
        $arguments = [];
        foreach (array_keys($container->findTaggedServiceIds(self::TAG)) as $id) {
            $arguments[] = new Reference($id);
        }

        $container->findDefinition(RelationshipsResolverInterface::class)->setArguments($arguments);
    }
}
