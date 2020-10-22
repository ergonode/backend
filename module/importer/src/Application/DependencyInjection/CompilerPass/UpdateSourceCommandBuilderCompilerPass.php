<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ergonode\Importer\Application\Provider\UpdateSourceCommandBuilderProvider;

class UpdateSourceCommandBuilderCompilerPass implements CompilerPassInterface
{
    public const TAG = 'import.source.update_source_command_builder_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(UpdateSourceCommandBuilderProvider::class)) {
            $this->processServices($container);
        }
    }

    private function processServices(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(UpdateSourceCommandBuilderProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
