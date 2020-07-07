<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel;

use Ergonode\Channel\Application\DependencyInjection\CompilerPass\ChannelGeneratorCompilerPass;
use Ergonode\SharedKernel\Application\AbstractModule;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 *
 */
class ErgonodeChannelBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ChannelGeneratorCompilerPass());
    }
}
