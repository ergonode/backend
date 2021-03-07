<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\ValueObject;

class DateTimeFormat implements DateFormatInterface
{
    private const FORMAT = 'yyyy-MM-dd\THH:mm:ssZ';

    public function getFormat(): string
    {
        return self::FORMAT;
    }

    public function getPhpFormat(): string
    {
        return \DateTimeInterface::RFC3339;
    }

    public static function isValid(string $value): bool
    {
        return $value === self::FORMAT;
    }
}
