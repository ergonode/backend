<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia;

use Ergonode\Core\Application\AbstractModule;
use Ergonode\Multimedia\Application\DependencyInjection\CompilerPass\MultimediaRelationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 */
class ErgonodeMultimediaBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new MultimediaRelationCompilerPass());
    }
}
