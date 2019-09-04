<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class CategoryTreeUpdateFormModel
{
    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=255, maxMessage="Category name is to long, It should have {{ limit }} character or less.")
     * })
     */
    public $name;

    /**
     * @var TreeNodeFormModel[]
     *
     * @Assert\Valid()
     */
    public $categories;

    /**
     */
    public function __construct()
    {
        $this->categories = [];
    }
}
