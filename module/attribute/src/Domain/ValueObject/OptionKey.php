<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\AbstractCode;

class OptionKey extends AbstractCode
{
    public function __construct(string $value)
    {
        parent::__construct(strtolower($value));
    }

    public static function isValid(string $value): bool
    {
        return parent::isValid(strtolower($value));
    }
}
