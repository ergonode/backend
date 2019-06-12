<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\DependencyInjection\CompilerPass;

use Ergonode\Attribute\Domain\Provider\AttributeValidationProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
class AttributeValidatorInterfaceCompilerPass implements CompilerPassInterface
{
    public const TAG = 'component.attribute.attribute_validation_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(AttributeValidationProvider::class)) {
            $this->processStrategies($container);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processStrategies(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(AttributeValidationProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
