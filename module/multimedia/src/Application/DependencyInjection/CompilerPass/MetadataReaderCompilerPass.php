<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ergonode\Multimedia\Infrastructure\Service\Metadata\MetadataReader;

class MetadataReaderCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.multimedia.metadata_reader';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(MetadataReader::class)) {
            $this->processTransformers($container);
        }
    }

    private function processTransformers(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(MetadataReader::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
