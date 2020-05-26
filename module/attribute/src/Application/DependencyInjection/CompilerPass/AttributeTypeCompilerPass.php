<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Attribute\Application\Provider\AttributeTypeProvider;

/**
 */
class AttributeTypeCompilerPass implements CompilerPassInterface
{
    public const TAG = 'attribute.domain.attribute_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(AttributeTypeProvider::class)) {
            $this->processHandler($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processHandler(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(AttributeTypeProvider::class);
        $services = $container->findTaggedServiceIds(self::TAG);

        $arguments = [];
        foreach ($services as $id => $service) {
            $arguments[] = $id;
            $container->removeDefinition($id);
        }

        $definition->setArguments($arguments);
    }
}
