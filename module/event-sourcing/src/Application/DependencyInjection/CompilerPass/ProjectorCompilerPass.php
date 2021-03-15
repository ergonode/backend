<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\EventSourcing\Infrastructure\Projector\ProjectorProvider;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Ergonode\SharedKernel\Domain\DomainEventInterface;

class ProjectorCompilerPass implements CompilerPassInterface
{
    public const TAG = 'ergonode.event_sourcing.projector';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ProjectorProvider::class)) {
            $this->processServices($container);
        }
    }

    private function processServices(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ProjectorProvider::class);
        $serviceIds = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($serviceIds) as $serviceId) {
            $service = $container->findDefinition($serviceId);
            $className = $service->getClass();
            $reflection = new \ReflectionClass($className);

            if (!$reflection->hasMethod('__invoke')) {
                throw new RuntimeException(
                    sprintf(
                        'Invalid projector "%s": class "%s" method "__invoke" does not exist.',
                        $serviceId,
                        $reflection->getName(),
                    )
                );
            }

            $method = $reflection->getMethod('__invoke');

            if (1 !== $method->getNumberOfRequiredParameters()) {
                throw new RuntimeException(
                    sprintf(
                        'Invalid projector "%s": class "%s: method "__invoke()" required one argument "%s"',
                        $serviceId,
                        $reflection->getName(),
                        DomainEventInterface::class
                    )
                );
            }

            $parameters = $method->getParameters();

            $class = $parameters[0]->getClass();

            if (null === $class) {
                throw new RuntimeException(
                    sprintf(
                        'Invalid ReflectionClass "%s" : class "%s: parameters',
                        $serviceId,
                        $reflection->getName(),
                    )
                );
            }

            $eventClass = $class->getName();
            $definition->addMethodCall('add', [new Reference($serviceId), $eventClass]);
        }
    }
}
