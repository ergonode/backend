<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterTypeProvider;

class FileWriterCompilerPass implements CompilerPassInterface
{
    public const TAG = 'export.export_file.writer_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(WriterProvider::class)) {
            $this->processProvider($container);
        }

        if ($container->has(WriterTypeProvider::class)) {
            $this->processType($container);
        }
    }

    private function processProvider(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(WriterProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }

    private function processType(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(WriterTypeProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
