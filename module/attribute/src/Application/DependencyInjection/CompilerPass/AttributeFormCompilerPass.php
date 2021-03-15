<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\DependencyInjection\CompilerPass;

use Ergonode\Attribute\Application\Provider\AttributeFormProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AttributeFormCompilerPass implements CompilerPassInterface
{
    public const TAG = 'attribute.form.attribute_form_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(AttributeFormProvider::class)) {
            $this->processHandler($container);
        }
    }

    private function processHandler(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(AttributeFormProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
