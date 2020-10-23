<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Model\Tree;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryTreeUpdateFormModel
{
    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=255,
     *       maxMessage="Category tree name is too long. It should contain {{ limit }} characters or less."
     *     )
     * })
     */
    public array $name;

    /**
     * @var TreeNodeFormModel[]
     *
     * @Assert\Valid()
     */
    public array $categories;

    public function __construct()
    {
        $this->name = [];
        $this->categories = [];
    }
}
