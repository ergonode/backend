<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Builder\LanguageTree;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\LanguageNode;

class LanguageTreeBuilder
{
    private LanguageQueryInterface $query;

    public function __construct(LanguageQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @throws \Exception
     */
    public function build(LanguageNode $node): NestedSetTree
    {
        $nestedSetTree = new NestedSetTree();
        $nestedSetTree->addRoot(
            $node->getLanguageId(),
            $this->query->getLanguageById($node->getLanguageId()->getValue())['code']
        );


        foreach ($node->getChildren() as $child) {
            $this->buildBranch($nestedSetTree, $child, $node);
        }


        return $nestedSetTree;
    }

    /**
     * @throws \Exception
     */
    private function buildBranch(NestedSetTree $nestedSetTree, LanguageNode $node, LanguageNode $parent): void
    {
        $nestedSetTree->addNode(
            $node->getLanguageId(),
            $this->query->getLanguageById($node->getLanguageId()->getValue())['code'],
            $parent->getLanguageId()
        );

        foreach ($node->getChildren() as $child) {
            $this->buildBranch($nestedSetTree, $child, $node);
        }
    }
}
