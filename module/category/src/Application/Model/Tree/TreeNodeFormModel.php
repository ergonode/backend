<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Model\Tree;

use Ergonode\Category\Application\Validator as CategoryAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\GroupSequence({"TreeNodeFormModel", "Category"})
 */
class TreeNodeFormModel
{
    /**
     * @Assert\NotBlank(message="Category id is required")
     * @Assert\Uuid()
     *
     * @CategoryAssert\CategoryExists(groups={"Category"})
     */
    public ?string $categoryId;

    /**
     * @var TreeNodeFormModel[]
     *
     * @Assert\Valid()
     */
    public array $children;

    public function __construct()
    {
        $this->categoryId = null;
        $this->children = [];
    }
}
