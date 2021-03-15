<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Importer\Infrastructure\JMS\Serializer\Handler\ConverterInterfaceHandler;

class ConverterCompilerPass implements CompilerPassInterface
{
    public const TAG = 'transformer.converter.converter_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ConverterInterfaceHandler::class)) {
            $this->processHandler($container);
        }
    }

    private function processHandler(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ConverterInterfaceHandler::class);
        $services = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($services) as $id) {
            $arguments = [$id];
            $definition->addMethodCall('set', $arguments);
            $container->removeDefinition($id);
        }
    }
}
