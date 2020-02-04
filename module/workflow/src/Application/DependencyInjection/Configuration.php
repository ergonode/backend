<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\DependencyInjection;

use Ergonode\Condition\Application\DependencyInjection\AddConditionsNodeSection;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('ergonode_workflow');
        $rootNode = $treeBuilder->getRootNode();
        AddConditionsNodeSection::addSection($rootNode);

        return $treeBuilder;
    }
}
