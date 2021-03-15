<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use ReflectionClass;
use Ergonode\Condition\Domain\ConditionInterface;

class AddConditionsNodeSection
{
    public static function addSection(ArrayNodeDefinition $node): void
    {
        /** @phpstan-ignore-next-line */
        $node
            ->children()
                ->arrayNode('conditions')
                    ->scalarPrototype()
                        ->validate()
                                ->ifTrue(function ($v) {
                                    return !class_exists($v);
                                })
                                ->thenInvalid('Condition  class %s does not exists')
                        ->end()
                        ->validate()
                            ->ifTrue(function ($v) {
                                $class = new ReflectionClass($v);

                                return !$class->implementsInterface(ConditionInterface::class);
                            })
                            ->thenInvalid('Class %s does not implement  '.ConditionInterface::class)
                        ->end()
                        ->validate()
                            ->ifTrue(function ($v) {
                                $class = new ReflectionClass($v);

                                return !$class->hasConstant('TYPE');
                            })
                            ->thenInvalid('Class %s doesn\'t have constant TYPE')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
