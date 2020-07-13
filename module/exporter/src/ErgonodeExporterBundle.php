<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter;

use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\CreateExportProfileCommandBuilderCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileTypeCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileFormFactoryCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProcessCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\UpdateExportProfileCommandBuilderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 */
class ErgonodeExporterBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new ExportProfileTypeCompilerPass());
        $container->addCompilerPass(new ExportProfileFormFactoryCompilerPass());
        $container->addCompilerPass(new CreateExportProfileCommandBuilderCompilerPass());
        $container->addCompilerPass(new UpdateExportProfileCommandBuilderCompilerPass());
        $container->addCompilerPass(new ExportProcessCompilerPass());
    }
}
