<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\DependencyInjection\CompilerPass;

use Ergonode\Designer\Infrastructure\Provider\TemplateGeneratorProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TemplateGeneratorStrategyCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.template_generator.template_generator_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(TemplateGeneratorProvider::class)) {
            $this->processTransformers($container);
        }
    }

    private function processTransformers(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(TemplateGeneratorProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
