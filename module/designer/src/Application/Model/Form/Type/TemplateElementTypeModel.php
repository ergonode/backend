<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Model\Form\Type;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Attribute\Infrastructure\Validator as CustomAssert;

/**
 */
class TemplateElementTypeModel
{
    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @CustomAssert\AttributeExists();
     */
    public $id;

    /**
     * @var int
     *
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Number only")
     * @Assert\GreaterThanOrEqual(value="0")
     * @Assert\LessThan(value="4")
     */
    public $x;

    /**
     * @var int
     *
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Number only")
     * @Assert\GreaterThanOrEqual(value="0")
     * @Assert\LessThan(value="100")
     */
    public $y;

    /**
     * @var int
     *
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Number only")
     * @Assert\GreaterThanOrEqual(value="0")
     * @Assert\LessThan(value="8")
     */
    public $width;

    /**
     * @var int
     *
     * @Assert\Regex(pattern="/^[0-9]*$/", message="Number only")
     * @Assert\GreaterThanOrEqual(value="0")
     * @Assert\LessThan(value="20")
     */
    public $height;

    /**
     * @var bool
     */
    public $required;
}
