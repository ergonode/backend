<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeUnit\Domain\Validator;

use Ergonode\Attribute\Domain\AttributeValidatorInterface;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\AttributeUnit\Domain\Entity\UnitAttribute;

/**
 */
class UnitAttributeValidator implements AttributeValidatorInterface
{
    /**
     * @param AbstractAttribute|UnitAttribute $attribute
     * @param mixed                           $value
     *
     * @return bool
     */
    public function isValid(AbstractAttribute $attribute, $value): bool
    {
        return !(\strlen($value) > 255);
    }

    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof UnitAttribute;
    }
}
