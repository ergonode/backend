<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Validator;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\AttributeValidatorInterface;

/**
 */
class TextAttributeValidator implements AttributeValidatorInterface
{
    /**
     * @param AbstractAttribute $attribute
     * @param mixed             $value
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
        return $attribute instanceof TextAttribute;
    }
}
