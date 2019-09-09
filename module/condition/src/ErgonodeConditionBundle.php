<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition;

use Ergonode\Core\Application\AbstractModule;
use Ergonode\Segment\Application\DependencyInjection\CompilerPass\SegmentConditionConfiguratorCompilerPass;
use Ergonode\Segment\Application\DependencyInjection\CompilerPass\SegmentGeneratorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 *
 */
class ErgonodeConditionBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

//        $container->addCompilerPass(new SegmentGeneratorCompilerPass());
//        $container->addCompilerPass(new SegmentConditionConfiguratorCompilerPass());
    }
}
