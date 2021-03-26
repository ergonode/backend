<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition;

use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ConditionCalculatorCompilerPass;
use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ConditionConfiguratorCompilerPass;
use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ConditionConstraintCompilerPass;
use Ergonode\SharedKernel\Application\AbstractModule;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeConditionBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ConditionConfiguratorCompilerPass());
        $container->addCompilerPass(new ConditionCalculatorCompilerPass());
        $container->addCompilerPass(new ConditionConstraintCompilerPass());
    }
}
