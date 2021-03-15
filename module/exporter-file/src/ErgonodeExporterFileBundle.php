<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile;

use Ergonode\SharedKernel\Application\AbstractModule;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\ExporterFile\Application\DependencyInjection\CompilerPass\FileWriterCompilerPass;

class ErgonodeExporterFileBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new FileWriterCompilerPass());
    }
}
