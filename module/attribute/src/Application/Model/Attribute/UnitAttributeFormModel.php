<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Model\Attribute;

use Ergonode\Attribute\Application\Model\Attribute\Property\UnitAttributePropertyModel;
use Symfony\Component\Validator\Constraints as Assert;

class UnitAttributeFormModel extends AttributeFormModel
{
    /**
     * @Assert\Valid()
     */
    public UnitAttributePropertyModel $parameters;

    public function __construct()
    {
        $this->parameters = new UnitAttributePropertyModel();
    }
}
