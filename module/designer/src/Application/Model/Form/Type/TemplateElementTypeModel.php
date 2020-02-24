<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Model\Form\Type;

use Ergonode\Designer\Application\Model\Form\Type\Property\AttributeElementPropertyTypeModel;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class TemplateElementTypeModel
{
    /**
     * @var Position | null
     */
    public ?Position $position;

    /**
     * @var Size | null
     */
    public ?Size $size;

    /**
     * @var string | null
     *
     * @Assert\NotBlank()
     */
    public ?string $type;

    /**
     * @var AttributeElementPropertyTypeModel | null
     *
     * @Assert\Valid()
     */
    public ?AttributeElementPropertyTypeModel $properties;

    /**
     */
    public function __construct()
    {
        $this->position = null;
        $this->size = null;
        $this->type = null;
        $this->properties = null;
    }
}
