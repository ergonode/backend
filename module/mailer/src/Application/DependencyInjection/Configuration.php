<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Application\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ergonode_mailer');

        /** @phpstan-ignore-next-line */
        $treeBuilder
            ->getRootNode()
                ->children()
                    ->arrayNode('default')
                        ->children()
                            ->scalarNode('from')
                                ->defaultNull()
                            ->end()
                            ->scalarNode('replyTo')
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('messenger')
                        ->validate()
                            ->ifTrue(
                                fn (array $messenger): bool =>
                                    $messenger['enabled'] && !isset($messenger['transport_name'])
                            )
                            ->thenInvalid('transport_name has to be defined for enabled messenger.')
                        ->end()
                        ->isRequired()
                        ->canBeDisabled()
                        ->children()
                            ->scalarNode('transport_name')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
