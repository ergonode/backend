<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer;

use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceFormFactoryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceImportCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\CreateSourceCommandBuilderCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\UpdateSourceCommandBuilderCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\AttributeActionProcessorCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ImportActionCompilerPass;

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
        $container->addCompilerPass(new SourceFormFactoryCompilerPass());
        $container->addCompilerPass(new ServiceImportCompilerPass());
        $container->addCompilerPass(new CreateSourceCommandBuilderCompilerPass());
        $container->addCompilerPass(new UpdateSourceCommandBuilderCompilerPass());
        $container->addCompilerPass(new AttributeActionProcessorCompilerPass());
        $container->addCompilerPass(new ImportActionCompilerPass());
    }
}
