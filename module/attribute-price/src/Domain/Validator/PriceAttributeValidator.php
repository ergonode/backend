<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributePrice\Domain\Validator;

use Ergonode\Attribute\Domain\AttributeValidatorInterface;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\AttributePrice\Domain\Entity\PriceAttribute;

/**
 */
class PriceAttributeValidator implements AttributeValidatorInterface
{
    /**
     * @param AbstractAttribute|PriceAttribute $attribute
     * @param mixed                            $value
     *
     * @return bool
     */
    public function isValid(AbstractAttribute $attribute, $value): bool
    {
        $value = str_replace(',', '.', $value);

        return is_numeric($value) && $value >= 0;
    }

    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof PriceAttribute;
    }
}
