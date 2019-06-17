<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Model\Form\Type;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
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
     * @var Position
     */
    public $position;

    /**
     * @var Size
     */
    public $size;

    /**
     * @var bool
     */
    public $required;
}
