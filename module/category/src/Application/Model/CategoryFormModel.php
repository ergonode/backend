<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Model;

use Ergonode\Category\Application\Validator as CategoryAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryFormModel
{
    /**
     * @Assert\NotBlank(
     *     message="System name is required",
     *     groups={"Create"}
     *     )
     *
     * @CategoryAssert\CategoryCodeConstraint()
     * @CategoryAssert\UniqueCategoryCodeConstraint(
     *     groups={"Create"}
     * )
     */
    public ?string $code = null;

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
