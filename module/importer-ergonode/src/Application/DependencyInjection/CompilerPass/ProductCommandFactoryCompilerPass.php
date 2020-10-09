<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Application\DependencyInjection\CompilerPass;

use Ergonode\ImporterErgonode\Infrastructure\Factory\Product\ProductCommandFactoryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
final class ProductCommandFactoryCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.ergonode-importer.product_command_factory_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ProductCommandFactoryInterface::class)) {
            $this->processTransformers($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processTransformers(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(ProductCommandFactoryInterface::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
