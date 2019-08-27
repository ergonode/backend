<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Command;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AddCategoryCommand
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\CategoryTreeId")
     */
    private $treeId;

    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private $categoryId;

    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private $parentId;

    /**
     * @param CategoryTreeId $treeId
     * @param CategoryId     $parentId
     * @param CategoryId     $categoryId
     *
     * @throws \Exception
     */
    public function __construct(CategoryTreeId $treeId, CategoryId $parentId, CategoryId $categoryId)
    {
        $this->treeId = $treeId;
        $this->categoryId = $categoryId;
        $this->parentId = $parentId;
    }

    /**
     * @return CategoryTreeId
     */
    public function getTreeId(): CategoryTreeId
    {
        return $this->treeId;
    }

    /**
     * @return CategoryId
     */
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    /**
     * @return CategoryId
     */
    public function getParentId(): CategoryId
    {
        return $this->parentId;
    }
}
