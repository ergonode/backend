<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Model\Form\Type;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Symfony\Component\Validator\Constraints as Assert;

class TemplateElementTypeModel
{
    public ?Position $position;

    public ?Size $size;

    /**
     * @Assert\NotBlank()
     */
    public ?string $type;

    /**
     * @Assert\Valid()
     */
    public ?object $properties;

    public function __construct()
    {
        $this->position = null;
        $this->size = null;
        $this->type = null;
        $this->properties = null;
    }
}
