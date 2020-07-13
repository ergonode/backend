<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Model;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Category\Infrastructure\Validator as CategoryAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class CategoryFormModel
{
    /**
     * @var CategoryCode
     *
     * @Assert\NotBlank(
     *     message="Category code is required",
     *     groups={"Create"}
     *     )
     * @Assert\Length(
     *     max=64,
     *     maxMessage="Maximum number of characters is 64",
     *     groups={"Create"}
     *     )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9-_]+$\b/i",
     *     message="Category code can have only letters, digits or underscore symbol",
     *     groups={"Create"}
     *  )
     *
     * @CategoryAssert\CategoryCode();
     */
    public ?CategoryCode $code = null;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=32, maxMessage="Category name is to long, It should have {{ limit }} character or less.")
     * })
     */
    public array $name = [];
}
