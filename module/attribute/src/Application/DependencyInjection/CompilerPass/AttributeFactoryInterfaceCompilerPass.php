<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\DependencyInjection\CompilerPass;

use Ergonode\Attribute\Domain\Provider\AttributeFactoryProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
class AttributeFactoryInterfaceCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.attribute.attribute_factory_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(AttributeFactoryProvider::class)) {
            $this->processProvider($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processProvider(ContainerBuilder $container): void
    {
        $arguments = [];
        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $container->findDefinition(AttributeFactoryProvider::class)->setArguments($arguments);
    }
}
