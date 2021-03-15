<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\DependencyInjection\CompilerPass;

use Ergonode\Core\Application\Nelmio\ExternalDocDescriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExternalDocDescriberCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('nelmio_api_doc.describers.config')) {
            return;
        }

        $container->getDefinition('nelmio_api_doc.describers.config')->setClass(ExternalDocDescriber::class);
    }
}
