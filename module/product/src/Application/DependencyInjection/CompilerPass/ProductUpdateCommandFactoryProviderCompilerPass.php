<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ergonode\Product\Infrastructure\Provider\UpdateProductCommandFactoryProvider;

class ProductUpdateCommandFactoryProviderCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.product.product_update_command_factory';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(UpdateProductCommandFactoryProvider::class)) {
            $this->processStrategies($container);
        }
    }

    private function processStrategies(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(UpdateProductCommandFactoryProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
