<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\AbstractCode;

class SystemAttributeCode extends AttributeCode
{

    public static function isValid(string $value): bool
    {
        $value = strtolower($value);

        return AbstractCode::isValid($value)
            && preg_match(self::SYSTEM_ATTRIBUTE_PATTERN, $value);
    }
}
