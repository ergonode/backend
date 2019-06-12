<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeDate\Domain\Validator;

use Ergonode\Attribute\Domain\AttributeValidatorInterface;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\AttributeDate\Domain\Entity\DateAttribute;

/**

 */
class DateAttributeValidator implements AttributeValidatorInterface
{
    /**
     * @param AbstractAttribute|DateAttribute $attribute
     * @param mixed                           $value
     *
     * @return bool
     */
    public function isValid(AbstractAttribute $attribute, $value): bool
    {
        $format = $attribute->getFormat()->getPhpFormat();
        $dt = \DateTime::createFromFormat($format, $value);

        return $dt !== false && !array_sum($dt::getLastErrors());
    }

    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof DateAttribute;
    }
}
