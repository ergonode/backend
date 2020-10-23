<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\ValueObject;

class SegmentCode
{
    private string $value;

    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('Invalid segment code value "%s"', $value));
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function isValid(string $value): bool
    {
        return '' !== $value
            && strlen($value) <= 100;
    }
}
