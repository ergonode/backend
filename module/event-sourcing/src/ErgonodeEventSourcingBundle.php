<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing;

use Ergonode\Core\Application\AbstractModule;
use Ergonode\EventSourcing\Application\DependencyInjection\CompilerPass\DomainEventProjectorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 */
class ErgonodeEventSourcingBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new DomainEventProjectorCompilerPass());
    }
}
