<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia;

use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Multimedia\Application\DependencyInjection\CompilerPass\MultimediaRelationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Multimedia\Application\DependencyInjection\CompilerPass\MetadataReaderCompilerPass;

class ErgonodeMultimediaBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new MetadataReaderCompilerPass());
        $container->addCompilerPass(new MultimediaRelationCompilerPass());
    }
}
