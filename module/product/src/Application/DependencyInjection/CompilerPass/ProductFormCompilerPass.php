<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Product\Application\Provider\ProductFormProvider;
use Symfony\Component\DependencyInjection\Reference;

class ProductFormCompilerPass implements CompilerPassInterface
{
    public const TAG = 'product.application.product_form_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ProductFormProvider::class)) {
            $this->processHandler($container);
        }
    }

    private function processHandler(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(ProductFormProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
