<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\DependencyInjection\CompilerPass;

use Ergonode\Exporter\Infrastructure\Provider\ExportProfileTypeDictionaryProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ExportProfileCompilerPass implements CompilerPassInterface
{
    public const TAG = 'export.export_profile.export_profile_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has(ExportProfileTypeDictionaryProvider::class)) {
            $this->processServices($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processServices(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(ExportProfileTypeDictionaryProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);
        $translator = $container->findDefinition(TranslatorInterface::class);

        $arguments[] = $translator;
        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }
        $definition->setArguments($arguments);
    }
}
