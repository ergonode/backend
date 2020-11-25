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
    private const PATTERN = '/^[a-zA-Z0-9-_]+$\b/i';

    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('Invalid category code value "%s"', $value));
        }
        parent::__construct($value);
    }

    public static function isValid(string $value): bool
    {
        if (preg_match(self::PATTERN, $value, $matches) === 0) {
            return false;
        }

        return parent::isValid($value);
    }
}
