<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute;

use Ergonode\Attribute\Application\DependencyInjection\CompilerPass\AttributeFactoryInterfaceCompilerPass;
use Ergonode\Attribute\Application\DependencyInjection\CompilerPass\AttributeUpdaterInterfaceCompilerPass;
// phpcs:ignore
use Ergonode\Attribute\Application\DependencyInjection\CompilerPass\AttributeValueConstraintStrategyInterfaceCompilerPass;
use Ergonode\Core\Application\AbstractModule;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 */
class ErgonodeAttributeBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(new AttributeFactoryInterfaceCompilerPass())
            ->addCompilerPass(new AttributeUpdaterInterfaceCompilerPass())
            ->addCompilerPass(new AttributeValueConstraintStrategyInterfaceCompilerPass());
    }
}
