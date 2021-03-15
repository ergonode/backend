<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product;

use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\AttributeColumnStrategyStrategyCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\AttributeDataSetQueryBuilderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductCreateCommandFactoryProviderCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductUpdateCommandFactoryProviderCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductTypeCompilerPass;
use Ergonode\Product\Application\DependencyInjection\CompilerPass\ProductFormCompilerPass;

class ErgonodeProductBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ProductFormCompilerPass());
        $container->addCompilerPass(new ProductTypeCompilerPass());
        $container->addCompilerPass(new AttributeColumnStrategyStrategyCompilerPass());
        $container->addCompilerPass(new AttributeDataSetQueryBuilderCompilerPass());
        $container->addCompilerPass(new ProductCreateCommandFactoryProviderCompilerPass());
        $container->addCompilerPass(new ProductUpdateCommandFactoryProviderCompilerPass());
    }
}
