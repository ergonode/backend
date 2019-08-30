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
     * @Assert\NotBlank(message="Category code is required")
     * @Assert\Regex(pattern="/^[a-zA-Z0-9-_]+$/i", message="Category tree code can have only letters, digits or underscore symbol")
     * @Assert\Length(min="3", max="64")
     */
    public $code;


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
