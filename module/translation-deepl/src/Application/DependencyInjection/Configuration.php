<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\TranslationDeepl\Application\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ergonode_translation_deepl');

        /** @phpstan-ignore-next-line */
        $treeBuilder
            ->getRootNode()
                ->children()
                    ->scalarNode('authentication_key')->isRequired()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
