<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core;

use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Core\Application\DependencyInjection\CompilerPass\RelationshipStrategyInterfaceCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeCoreBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RelationshipStrategyInterfaceCompilerPass());
    }
}
