<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\DependencyInjection\CompilerPass;

use Ergonode\Importer\Infrastructure\Provider\SourceTypeProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SourceTypeCompilerPass implements CompilerPassInterface
{
    public const TAG = 'import.source.import_source_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(SourceTypeProvider::class)) {
            $this->processServices($container);
        }
    }

    private function processServices(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(SourceTypeProvider::class);
        $services = $container->findTaggedServiceIds(self::TAG);
        $types = [];

        foreach (array_keys($services) as $id) {
            $types[] = $container->getDefinition($id)->getClass()::getType();
        }

        $definition->setArguments($types);
    }
}
