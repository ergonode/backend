<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader;

use Ergonode\Core\Application\AbstractModule;
use Ergonode\Reader\Application\DependencyInjection\CompilerPass\ReaderGeneratorStrategyCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 */
class ErgonodeReaderBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ReaderGeneratorStrategyCompilerPass());
    }
}
