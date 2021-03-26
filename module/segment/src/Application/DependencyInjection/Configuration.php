<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Ergonode\Condition\Application\DependencyInjection\AddConditionsNodeSection;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('ergonode_segment');
        $rootNode = $treeBuilder->getRootNode();
        AddConditionsNodeSection::addSection($rootNode);

        return $treeBuilder;
    }
}
