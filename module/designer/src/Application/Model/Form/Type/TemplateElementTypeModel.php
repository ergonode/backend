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
    public Position $position;

    /**
     * @var Size
     */
    public Size $size;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public string $type;

    /**
     * @var array
     *
     * @Assert\Valid()
     */
    public array $properties = [];
}
