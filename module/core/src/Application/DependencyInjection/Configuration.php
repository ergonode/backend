<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ergonode_core');

        /** @phpstan-ignore-next-line */
        $treeBuilder
            ->getRootNode()
                ->children()
                    ->booleanNode('use_async_bus')->isRequired()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
