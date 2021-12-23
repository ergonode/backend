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
     * @CategoryAssert\CategoryCode()
     * @CategoryAssert\CategoryCodeUnique(
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
     *       max=128,
     *       maxMessage="Category name is too long. It should contain {{ limit }} characters or less."
     *     )
     * })
     */
    public array $name = [];
}
