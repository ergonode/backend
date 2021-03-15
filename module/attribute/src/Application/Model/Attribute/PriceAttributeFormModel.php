<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Model\Attribute;

use Ergonode\Attribute\Application\Model\Attribute\Property\PriceAttributePropertyModel;
use Symfony\Component\Validator\Constraints as Assert;

class PriceAttributeFormModel extends AttributeFormModel
{
    /**
     * @Assert\Valid()
     */
    public PriceAttributePropertyModel $parameters;

    public function __construct()
    {
        $this->parameters = new PriceAttributePropertyModel();
    }
}
