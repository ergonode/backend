<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition;

use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ConditionConfiguratorCompilerPass;
use Ergonode\Core\Application\AbstractModule;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 */
class ErgonodeConditionBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ConditionConfiguratorCompilerPass());
    }
}
