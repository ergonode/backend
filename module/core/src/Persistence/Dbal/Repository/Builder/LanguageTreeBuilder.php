<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Persistence\Dbal\Repository\Builder;

use Ergonode\Core\Domain\ValueObject\LanguageNode;

/**
 */
class LanguageTreeBuilder
{
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
        $nestedSetTree->addRoot($node->getLanguage()->getCode());

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
        $nestedSetTree->addNode($node->getLanguage()->getCode(), $node->getParent()->getLanguage()->getCode());
        foreach ($node->getChildren() as $child) {
            $this->buildBranch($nestedSetTree, $child);
        }
    }
}
