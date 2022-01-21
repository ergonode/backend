<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow;

use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Workflow\Application\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeWorkflowBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CompilerPass\WorkflowFormCompilerPass());
        $container->addCompilerPass(new CompilerPass\WorkflowTypeCompilerPass());
        $container->addCompilerPass(new CompilerPass\CreateWorkflowCommandFactoryProviderInterfaceCompilerPass());
        $container->addCompilerPass(new CompilerPass\UpdateWorkflowCommandFactoryProviderInterfaceCompilerPass());
    }
}
