<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\EventSourcing\Infrastructure\Projector\ProjectorProvider;
use Symfony\Component\DependencyInjection\Reference;

class ProjectorCompilerPass implements CompilerPassInterface
{
    public const TAG = 'ergonode.es.projector';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ProjectorProvider::class)) {
            $this->processServices($container);
        }
    }

    private function processServices(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ProjectorProvider::class);
        $projectors = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($projectors) as $id) {
            $object = new \ReflectionClass($id);
            $method = $object->getMethod('__invoke');
            $parameters = $method->getParameters();
            $eventClass = (string) $parameters[0]->getType();

            $definition->addMethodCall('add', [new Reference($id), $eventClass]);
        }
    }
}
