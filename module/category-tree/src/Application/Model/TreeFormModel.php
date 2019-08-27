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
class TreeFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Category name is required")
     * @Assert\Length(min="3", max="32")
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
