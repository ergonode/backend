<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Application\DependencyInjection\CompilerPass;

use Ergonode\Transformer\Infrastructure\Generator\TransformerGenerator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
class TransformerGeneratorStrategyCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.transformer_generator.transformer_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(TransformerGenerator::class)) {
            $this->processTransformers($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processTransformers(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(TransformerGenerator::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
