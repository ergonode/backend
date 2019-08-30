<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Model\Form\Type;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class TemplateElementTypeModel
{
    /**
     * @var Position
     */
    public $position;

    /**
     * @var Size
     */
    public $size;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $type;

    /**
     * @var mixed
     *
     * @Assert\Valid()
     */
    public $properties = [];
}
