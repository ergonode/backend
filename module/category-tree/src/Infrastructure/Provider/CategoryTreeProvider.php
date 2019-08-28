<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Infrastructure\Provider;

use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;

/**
 */
class CategoryTreeProvider
{
    /**
     * @var TreeRepositoryInterface
     */
    private $repository;

    /**
     * @param TreeRepositoryInterface $repository
     */
    public function __construct(TreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $code
     *
     * @return CategoryTree
     *
     * @throws \Exception
     */
    public function getTree(string $code): CategoryTree
    {
        $treeId = CategoryTreeId::fromKey($code);
        $tree = $this->repository->load($treeId);
        if (null === $tree) {
            $tree = new CategoryTree($treeId, $code);
            $this->repository->save($tree);
        }

        return $tree;
    }
}
