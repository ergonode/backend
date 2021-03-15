<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Attribute\Application\Provider\AttributeTypeProvider;

class AttributeTypeCompilerPass implements CompilerPassInterface
{
    public const TAG = 'attribute.domain.attribute_interface';

    /**
     * @throws \ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(AttributeTypeProvider::class)) {
            $this->processHandler($container);
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function processHandler(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(AttributeTypeProvider::class);
        $services = $container->findTaggedServiceIds(self::TAG);

        $arguments = [];
        foreach (array_keys($services) as $id) {
            $type = (new \ReflectionClass($id))->getConstant('TYPE');
            $arguments[] = $type;
            $container->removeDefinition($id);
        }

        $definition->setArguments($arguments);
    }
}
