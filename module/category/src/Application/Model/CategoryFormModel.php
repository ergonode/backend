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

class CategoryFormModel
{
    /**
     * @var CategoryCode
     *
     * @Assert\NotBlank(
     *     message="System name is required",
     *     groups={"Create"}
     *     )
     * @Assert\Length(
     *     max=64,
     *     maxMessage="System name is too long. It should contain {{ limit }} characters or less.",
     *     groups={"Create"}
     *     )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9-_]+$\b/i",
     *     message="System name can have only letters, digits or underscore symbol",
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
     *     @Assert\Length(
     *       max=32,
     *       maxMessage="Category name is too long. It should contain {{ limit }} characters or less."
     *     )
     * })
     */
    public array $name = [];
}
