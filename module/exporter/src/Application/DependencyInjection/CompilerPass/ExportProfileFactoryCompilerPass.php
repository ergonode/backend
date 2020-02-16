<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\DependencyInjection\CompilerPass;

use Ergonode\Exporter\Domain\Provider\ExportProfileProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
class ExportProfileFactoryCompilerPass implements CompilerPassInterface
{
    public const TAG = 'export.export_profile.export_profile_factory_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has(ExportProfileProvider::class)) {
            $this->processServices($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processServices(ContainerBuilder $container)
    {
        $arguments = [];
        $definition = $container->findDefinition(ExportProfileProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);


        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }
        $definition->setArguments($arguments);
    }
}
