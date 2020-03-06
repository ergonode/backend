<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Model\Property;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class DateAttributePropertyModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public ?string $format = null;
}
