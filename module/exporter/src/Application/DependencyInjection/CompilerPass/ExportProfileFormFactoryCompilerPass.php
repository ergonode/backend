<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\DependencyInjection\CompilerPass;

use Ergonode\Exporter\Application\Provider\ExportProfileFormFactoryProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
class ExportProfileFormFactoryCompilerPass implements CompilerPassInterface
{
    public const TAG = 'export.export_profile.export_profile_form_factory_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ExportProfileFormFactoryProvider::class)) {
            $this->processServices($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processServices(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(ExportProfileFormFactoryProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
