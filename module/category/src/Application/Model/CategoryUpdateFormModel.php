<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class CategoryUpdateFormModel
{
    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=32, maxMessage="Category name is to long, It should have {{ limit }} character or less.")
     * })
     */
    public array $name;

    /**
     */
    public function __construct()
    {
        $this->name = [];
    }
}
