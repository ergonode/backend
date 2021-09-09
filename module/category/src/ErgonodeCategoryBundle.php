<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category;

use Ergonode\Category\Application\DependencyInjection\CompilerPass as CompilerPass;
use Ergonode\SharedKernel\Application\AbstractModule;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeCategoryBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(new CompilerPass\CategoryFormCompilerPass())
            ->addCompilerPass(new CompilerPass\CategoryTypeCompilerPass())
            ->addCompilerPass(new CompilerPass\CreateCategoryCommandFactoryProviderInterfaceCompilerPass())
            ->addCompilerPass(new CompilerPass\UpdateCategoryCommandFactoryProviderInterfaceCompilerPass());
    }
}
