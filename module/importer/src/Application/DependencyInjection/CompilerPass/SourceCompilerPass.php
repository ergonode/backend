<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ergonode\Importer\Infrastructure\Provider\SourceTypeDictionaryProvider;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 *
 */
class SourceCompilerPass implements CompilerPassInterface
{
    public const TAG = 'import.source.import_source_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(SourceTypeDictionaryProvider::class)) {
            $this->processServices($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processServices(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(SourceTypeDictionaryProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);
        $translator = $container->findDefinition(TranslatorInterface::class);

        $arguments[] = $translator;
        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
