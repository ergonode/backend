<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute;

use Ergonode\Attribute\Application\DependencyInjection\CompilerPass as CompilerPass;
use Ergonode\SharedKernel\Application\AbstractModule;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeAttributeBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(new CompilerPass\AttributeFormCompilerPass())
            ->addCompilerPass(new CompilerPass\AttributeTypeCompilerPass())
            ->addCompilerPass(new CompilerPass\CreateAttributeCommandFactoryProviderInterfaceCompilerPass())
            ->addCompilerPass(new CompilerPass\UpdateAttributeCommandFactoryProviderInterfaceCompilerPass())
            ->addCompilerPass(new CompilerPass\AttributeValueConstraintStrategyInterfaceCompilerPass());
    }
}
