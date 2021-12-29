<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel;

use Ergonode\SharedKernel\Application\AbstractModule;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\ChannelFormFactoryCompilerPass;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\ChannelTypeCompilerPass;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\CreateChannelCommandBuilderCompilerPass;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\UpdateChannelCommandBuilderCompilerPass;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\ExportProcessCompilerPass;

class ErgonodeChannelBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ChannelFormFactoryCompilerPass());
        $container->addCompilerPass(new ChannelTypeCompilerPass());
        $container->addCompilerPass(new CreateChannelCommandBuilderCompilerPass());
        $container->addCompilerPass(new UpdateChannelCommandBuilderCompilerPass());
        $container->addCompilerPass(new ExportProcessCompilerPass());
    }
}
