<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\DependencyInjection\CompilerPass;

use Ergonode\Workflow\Application\Provider\WorkflowFormProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WorkflowFormCompilerPass implements CompilerPassInterface
{
    public const TAG = 'workflow.form.workflow_form_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(WorkflowFormProvider::class)) {
            $this->processHandler($container);
        }
    }

    private function processHandler(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(WorkflowFormProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
