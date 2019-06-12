<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Model;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Category\Infrastructure\Validator as CategoryAssert;

/**
 */
class CategoryCreateFormModel
{
    /**
     * @var CategoryCode
     *
     * @Assert\NotBlank(message="Category code is required")
     * @Assert\Length(max=64)
     * @Assert\Regex(pattern="/^[a-zA-Z0-9-_]+$/i", message="Category code can have only letters, digits or underscore symbol")
     * @CategoryAssert\CategoryCode();
     */
    public $code;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=32, maxMessage="Category name is to long, It should have {{ limit }} character or less.")
     * })
     */
    public $name;

    /**
     * CategoryCreateFormModel constructor.
     */
    public function __construct()
    {
        $this->name = [];
    }
}
