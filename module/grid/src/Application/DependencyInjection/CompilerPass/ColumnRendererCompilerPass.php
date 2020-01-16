<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Application\DependencyInjection\CompilerPass;

use Ergonode\Grid\Renderer\RowRendererInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
class ColumnRendererCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.grid.renderer.column';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(RowRendererInterface::class)) {
            $this->processRenderers($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processRenderers(ContainerBuilder $container): void
    {
        $arguments = [];
        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $renderer) {
            $arguments[] = new Reference($id);
        }

        $container->findDefinition(RowRendererInterface::class)->setArguments($arguments);
    }
}
