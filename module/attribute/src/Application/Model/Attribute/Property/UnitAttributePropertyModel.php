<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Model\Attribute\Property;

use Symfony\Component\Validator\Constraints as Assert;

class UnitAttributePropertyModel
{
    /**
     * @Assert\NotBlank()
     */
    public ?string $unit = null;
}
