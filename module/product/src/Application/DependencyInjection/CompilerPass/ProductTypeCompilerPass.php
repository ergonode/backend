<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Product\Application\Provider\ProductTypeProvider;

class ProductTypeCompilerPass implements CompilerPassInterface
{
    public const TAG = 'product.domain.product_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ProductTypeProvider::class)) {
            $this->processHandler($container);
        }
    }

    private function processHandler(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ProductTypeProvider::class);
        $services = $container->findTaggedServiceIds(self::TAG);

        $arguments = [];
        foreach (array_keys($services) as $id) {
            $arguments[] = $id;
            $container->removeDefinition($id);
        }

        $definition->setArguments($arguments);
    }
}
