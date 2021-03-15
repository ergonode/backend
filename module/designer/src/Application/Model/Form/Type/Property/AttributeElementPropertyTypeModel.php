<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Model\Form\Type\Property;

use Ergonode\Attribute\Application\Validator\AttributeExists;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\GroupSequence({"AttributeElementPropertyTypeModel", "Attribute"})
 */
class AttributeElementPropertyTypeModel
{
    /**
     * @Assert\NotBlank(message="Attribute id is required")
     * @Assert\Uuid()
     *
     * @AttributeExists(groups={"Attribute"})
     */
    public ?string $attributeId;

    public ?bool $required;

    public function __construct()
    {
        $this->attributeId = null;
        $this->required = null;
    }
}
