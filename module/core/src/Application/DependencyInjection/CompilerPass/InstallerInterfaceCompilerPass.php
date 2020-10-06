<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ergonode\Core\Application\Installer\InstallerProvider;

/**
 */
class InstallerInterfaceCompilerPass implements CompilerPassInterface
{
    public const TAG = 'core.application.installer_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(InstallerProvider::class)) {
            $arguments = [];
            foreach ($container->findTaggedServiceIds(self::TAG) as $id => $strategy) {
                $arguments[] = new Reference($id);
            }

            $container->findDefinition(InstallerProvider::class)->setArguments($arguments);
        }
    }
}
