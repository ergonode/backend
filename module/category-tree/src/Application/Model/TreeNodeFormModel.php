<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Application\Model;

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
    public $categoryId;

    /**
     * @var TreeNodeFormModel[]
     *
     * @Assert\Valid()
     */
    public $childrens;

    /**
     */
    public function __construct()
    {
        $this->childrens = [];
    }
}
