<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\DependencyInjection\CompilerPass;

use Ergonode\Category\Application\Provider\CategoryTypeProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CategoryTypeCompilerPass implements CompilerPassInterface
{
    public const TAG = 'category.domain.category_interface';

    /**
     * @throws \ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(CategoryTypeProvider::class)) {
            $this->processHandler($container);
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function processHandler(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(CategoryTypeProvider::class);
        $services = $container->findTaggedServiceIds(self::TAG);

        $arguments = [];
        foreach (array_keys($services) as $id) {
            $type = (new \ReflectionClass($id))->getConstant('TYPE');
            $arguments[] = $type;
            $container->removeDefinition($id);
        }

        $definition->setArguments($arguments);
    }
}
