<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core;

use Ergonode\Core\Application\DependencyInjection\CompilerPass\ExternalDocDescriberCompilerPass;
use Ergonode\Core\Application\DependencyInjection\CompilerPass\SerializerCompilerPass;
use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Core\Application\DependencyInjection\CompilerPass\RelationshipStrategyInterfaceCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeCoreBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RelationshipStrategyInterfaceCompilerPass());
        $container->addCompilerPass(new ExternalDocDescriberCompilerPass());
        $container->addCompilerPass(new SerializerCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10);
    }
}
