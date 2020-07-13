<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\DependencyInjection\CompilerPass;

use Ergonode\Category\Application\Provider\CategoryFormProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
class CategoryFormCompilerPass implements CompilerPassInterface
{
    public const TAG = 'category.form.category_form_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(CategoryFormProvider::class)) {
            $this->processHandler($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processHandler(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(CategoryFormProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
