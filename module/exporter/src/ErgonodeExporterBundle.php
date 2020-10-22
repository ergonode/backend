<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter;

use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProcessCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeExporterBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ExportProcessCompilerPass());
    }
}
