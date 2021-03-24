<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Serializer\Normalizer\Fixtures;

abstract class AbstractClass
{
    public function __construct(string $a)
    {
    }

    public static function isValid(string $a): bool
    {
        return true;
    }

    public function getValue(): string
    {
        return '';
    }
}
