<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Model\Attribute;

use Ergonode\Attribute\Application\Model\Attribute\Property\DateAttributePropertyModel;
use Symfony\Component\Validator\Constraints as Assert;

class DateAttributeFormModel extends AttributeFormModel
{
    /**
     * @Assert\Valid()
     */
    public DateAttributePropertyModel $parameters;

    public function __construct()
    {
        $this->parameters = new DateAttributePropertyModel();
    }
}
