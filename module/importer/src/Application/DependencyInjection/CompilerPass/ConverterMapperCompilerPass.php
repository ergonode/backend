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
use Ergonode\Importer\Infrastructure\Provider\ConverterMapperProvider;

class ConverterMapperCompilerPass implements CompilerPassInterface
{
    public const TAG = 'transformer.converter.converter_mapper_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ConverterMapperProvider::class)) {
            $this->processTransformers($container);
        }
    }

    private function processTransformers(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(ConverterMapperProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
