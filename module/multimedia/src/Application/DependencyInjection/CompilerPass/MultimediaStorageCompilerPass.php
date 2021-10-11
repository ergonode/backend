<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MultimediaStorageCompilerPass implements CompilerPassInterface
{
    public const TAG = 'multimedia.storage';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(self::TAG)) {
            $this->processServices($container);
        }
    }

    private function processServices(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(self::TAG);
        $definition->setPublic(true);
    }
}
