<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Application\DependencyInjection\CompilerPass;

use Ergonode\Reader\Domain\Generator\ReaderGenerator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
class ReaderGeneratorStrategyCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.reader.reader_generator_strategy_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ReaderGenerator::class)) {
            $this->processTransformers($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processTransformers(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(ReaderGenerator::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
