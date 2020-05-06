<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Model\Tree;

use Ergonode\Category\Infrastructure\Validator\CategoryExists;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\GroupSequence({"TreeNodeFormModel", "Category"})
 */
class TreeNodeFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Category id is required")
     * @Assert\Uuid()
     *
     * @CategoryExists(groups={"Category"})
     */
    public ?string $categoryId;

    /**
     * @var TreeNodeFormModel[]
     *
     * @Assert\Valid()
     */
    public array $children;

    /**
     */
    public function __construct()
    {
        $this->categoryId = null;
        $this->children = [];
    }
}
