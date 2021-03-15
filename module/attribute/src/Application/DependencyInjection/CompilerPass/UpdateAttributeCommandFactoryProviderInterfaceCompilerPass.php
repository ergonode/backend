<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ergonode\Attribute\Infrastructure\Provider\UpdateAttributeCommandFactoryProvider;

class UpdateAttributeCommandFactoryProviderInterfaceCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.attribute.update_attribute_command_factory_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(UpdateAttributeCommandFactoryProvider::class)) {
            $this->processProvider($container);
        }
    }

    private function processProvider(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(UpdateAttributeCommandFactoryProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
