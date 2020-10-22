<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Builder\LanguageTree;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\LanguageNode;

class LanguageTreeBuilder
{
    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $query;

    /**
     * @param LanguageQueryInterface $query
     */
    public function __construct(LanguageQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param LanguageNode $node
     *
     * @return NestedSetTree
     *
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
            $this->buildBranch($nestedSetTree, $child);
        }


        return $nestedSetTree;
    }

    /**
     * @param NestedSetTree $nestedSetTree
     * @param LanguageNode  $node
     *
     * @throws \Exception
     */
    private function buildBranch(NestedSetTree $nestedSetTree, LanguageNode $node): void
    {
        $nestedSetTree->addNode(
            $node->getLanguageId(),
            $this->query->getLanguageById($node->getLanguageId()->getValue())['code'],
            $node->getParent()->getLanguageId()
        );

        foreach ($node->getChildren() as $child) {
            $this->buildBranch($nestedSetTree, $child);
        }
    }
}
