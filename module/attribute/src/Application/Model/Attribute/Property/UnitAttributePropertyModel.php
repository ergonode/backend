<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Model\Attribute\Property;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

/**
 */
class UnitAttributePropertyModel
{
    /**
     * @var UnitId|null
     *
     * @Assert\NotBlank()
     */
    public ?UnitId $unit = null;
}
