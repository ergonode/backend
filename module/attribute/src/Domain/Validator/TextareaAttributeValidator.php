<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Validator;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\AttributeValidatorInterface;

/**
 * Class TextAttributeValidator
 */
class TextareaAttributeValidator implements AttributeValidatorInterface
{
    /**
     * @param AbstractAttribute $attribute
     * @param mixed             $value
     *
     * @return bool
     */
    public function isValid(AbstractAttribute $attribute, $value): bool
    {
        return true;
    }

    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof TextareaAttribute;
    }
}
