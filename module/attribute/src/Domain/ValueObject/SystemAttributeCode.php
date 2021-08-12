<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\ValueObject;

class SystemAttributeCode extends AttributeCode
{
    public const SYSTEM_ATTRIBUTE_PREFIX = 'esa_';

    public static function isValid(string $value): bool
    {
        $value = strtolower($value);

        return parent::isValid($value)
            && 0 === strpos($value, self::SYSTEM_ATTRIBUTE_PREFIX);
    }
}
