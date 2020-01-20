<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Model\Form\Type\Property;

use Ergonode\Attribute\Infrastructure\Validator\AttributeExists;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\GroupSequence({"AttributeElementPropertyTypeModel", "Attribute"})
 */
class AttributeElementPropertyTypeModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Attribute id is required")
     * @Assert\Uuid()
     *
     * @AttributeExists(groups={"Attribute"})
     */
    public $attributeId;

    /**
     * @var bool
     */
    public $required;
}
