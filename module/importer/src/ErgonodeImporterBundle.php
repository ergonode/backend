<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer;

use Ergonode\Core\Application\AbstractModule;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceFactoryCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceFormFactoryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceImportCompilerPass;

/**
 */
class ErgonodeImporterBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new SourceCompilerPass());
        $container->addCompilerPass(new ServiceCompilerPass());
        $container->addCompilerPass(new SourceFactoryCompilerPass());
        $container->addCompilerPass(new SourceFormFactoryCompilerPass());
        $container->addCompilerPass(new ServiceImportCompilerPass());
    }
}
