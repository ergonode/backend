<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product;

use Ergonode\Core\Application\AbstractModule;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\AttributeColumnStrategyStrategyCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductFactoryProviderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 */
class ErgonodeProductBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AttributeColumnStrategyStrategyCompilerPass());
        $container->addCompilerPass(new ProductFactoryProviderCompilerPass());
    }
}
