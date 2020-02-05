<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow;

use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ProvideConditionDictionaryCompilerPass;
use Ergonode\Core\Application\AbstractModule;
use Ergonode\Workflow\Application\DependencyInjection\ErgonodeWorkflowExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 */
class ErgonodeWorkflowBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $compiler = new ProvideConditionDictionaryCompilerPass(
            ErgonodeWorkflowExtension::CONDITION_GROUP_NAME,
            ErgonodeWorkflowExtension::CONDITION_PARAMETER_NAME
        );

        $container->addCompilerPass($compiler);
    }
}
