<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
                        ->canBeDisabled()
                        ->children()
                            ->scalarNode('transport_name')->defaultValue('notification')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
