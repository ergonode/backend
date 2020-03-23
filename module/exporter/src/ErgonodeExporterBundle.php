<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter;

use Ergonode\Core\Application\AbstractModule;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileConstraintCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileFactoryCompilerPass;
use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProfileFormFactoryCompilerPass;
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
        $container->addCompilerPass(new ExportProfileCompilerPass());
        $container->addCompilerPass(new ExportProfileFactoryCompilerPass());
        $container->addCompilerPass(new ExportProfileConstraintCompilerPass());
        $container->addCompilerPass(new ExportProfileFormFactoryCompilerPass());
    }
}
