<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\DependencyInjection\CompilerPass;

use Ergonode\Channel\Infrastructure\Provider\ExportProcessorProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ExportProcessCompilerPass implements CompilerPassInterface
{
    public const TAG = 'export.export_processor_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ExportProcessorProvider::class)) {
            $this->processServices($container);
        }
    }

    private function processServices(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(ExportProcessorProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
