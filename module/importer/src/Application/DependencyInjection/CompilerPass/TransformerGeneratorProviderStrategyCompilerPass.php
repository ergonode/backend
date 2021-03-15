<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ergonode\Importer\Infrastructure\Provider\TransformerGeneratorProvider;

class TransformerGeneratorProviderStrategyCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.transformer_generator_strategy.transformer_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(TransformerGeneratorProvider::class)) {
            $this->processTransformers($container);
        }
    }

    private function processTransformers(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(TransformerGeneratorProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
