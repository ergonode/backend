<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\ValueObject;

interface DateFormatInterface
{
    public function getFormat(): string;
    public function getPhpFormat(): string;
    public static function isValid(string $value): bool;
}
