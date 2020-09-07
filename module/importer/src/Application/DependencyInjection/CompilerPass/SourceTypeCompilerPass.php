<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\DependencyInjection\CompilerPass;

use Ergonode\Importer\Infrastructure\Provider\SourceTypeProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 *
 */
class SourceTypeCompilerPass implements CompilerPassInterface
{
    public const TAG = 'import.source.import_source_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(SourceTypeProvider::class)) {
            $this->processServices($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processServices(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(SourceTypeProvider::class);
        $services = $container->findTaggedServiceIds(self::TAG);

        foreach ($services as $id => $service) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
