<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Grid\Application\DependencyInjection\CompilerPass\ColumnRendererCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeGridBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ColumnRendererCompilerPass());
    }
}
