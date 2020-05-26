<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Exporter\Application\Provider\ExportProfileTypeProvider;

/**
 */
class ExportProfileTypeCompilerPass implements CompilerPassInterface
{
    public const TAG = 'export.export_profile.export_profile_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ExportProfileTypeProvider::class)) {
            $this->processHandler($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processHandler(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ExportProfileTypeProvider::class);
        $services = $container->findTaggedServiceIds(self::TAG);

        $arguments = [];
        foreach ($services as $id => $service) {
            $arguments[] = $id;
            $container->removeDefinition($id);
        }

        $definition->setArguments($arguments);
    }
}
