<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\AbstractCode;

class CategoryCode extends AbstractCode
{
    public const PATTERN = '/^[a-zA-Z0-9-_]+$\b/i';

    public static function isValid(string $value): bool
    {
        if (preg_match(self::PATTERN, $value, $matches) === 0) {
            return false;
        }

        return parent::isValid($value);
    }
}
