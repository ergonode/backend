<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing;

use Ergonode\Core\Application\AbstractModule;
use Ergonode\EventSourcing\Application\DependencyInjection\Compiler\ResolveMessengerDefaultBusCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 */
class ErgonodeEventSourcingBundle extends AbstractModule
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(
                new ResolveMessengerDefaultBusCompilerPass()
            );
    }
}
