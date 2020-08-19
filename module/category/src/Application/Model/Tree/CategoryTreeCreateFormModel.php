<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Model\Tree;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Category\Infrastructure\Validator\UniqueCategoryTreeCode;

/**
 */
class CategoryTreeCreateFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Category tree code is required")
     * @Assert\Length(max=64)
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9-_]+$/i",
     *      message="Category tree code can have only letters, digits or underscore symbol"
     * )
     * @UniqueCategoryTreeCode()
     */
    public ?string $code;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=255,
     *       maxMessage="Category tree name is to long, It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $name;

    /**
     */
    public function __construct()
    {
        $this->code = null;
        $this->name = [];
    }
}
