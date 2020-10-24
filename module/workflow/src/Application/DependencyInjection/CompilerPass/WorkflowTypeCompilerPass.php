<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Workflow\Application\Provider\WorkflowTypeProvider;

class WorkflowTypeCompilerPass implements CompilerPassInterface
{
    public const TAG = 'workflow.domain.workflow_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(WorkflowTypeProvider::class)) {
            $this->processHandler($container);
        }
    }

    private function processHandler(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(WorkflowTypeProvider::class);
        $services = $container->findTaggedServiceIds(self::TAG);
        $types = [];
        foreach ($services as $id => $service) {
            $types[] = $container->getDefinition($id)->getClass()::getType();
        }

        $definition->setArguments($types);
    }
}
