<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Application\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ergonode_event_sourcing');

        /** @phpstan-ignore-next-line */
        $treeBuilder
            ->getRootNode()
                ->children()
                    ->integerNode('snapshot_frequency')
                        ->defaultValue(10)
                        ->validate()
                            ->ifTrue(fn (int $snapshotFrequency): bool => $snapshotFrequency <= 0)
                            ->thenInvalid('Snapshot frequency has to be positive integer.')
                        ->end()
                        ->info(
                            'Indicates how frequently snapshots of aggregate (every X events) are taken.
                            More frequent snapshots slow persist operations but make the read operations faster.'
                        )
                    ->end()
                    ->scalarNode('aggregate_root_cache')
                        ->info('Cache service id implementing `\Symfony\Component\Cache\Adapter\AdapterInterface`')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
