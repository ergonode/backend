<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Model\Form\Type\Property;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class SegmentElementPropertyTypeModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="32")
     */
    public $label;
}
