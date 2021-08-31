<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\AbstractCode;

class AttributeCode extends AbstractCode
{
    public const PATTERN = '/^([a-zA-Z0-9_]+)$/';

    public function __construct(string $value)
    {
        parent::__construct(strtolower($value));
    }

    public static function isValid(string $value): bool
    {
        $value = strtolower($value);

        return parent::isValid($value)
            && preg_match(self::PATTERN, $value);
    }
}
