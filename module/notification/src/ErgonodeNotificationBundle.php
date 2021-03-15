<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification;

use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Notification\Application\DependencyInjection\CompilerPass\NotificationStrategyInterfaceCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeNotificationBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(new NotificationStrategyInterfaceCompilerPass());
    }
}
