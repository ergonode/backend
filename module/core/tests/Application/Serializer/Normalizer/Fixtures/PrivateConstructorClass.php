<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Serializer\Normalizer\Fixtures;

class PrivateConstructorClass
{
    private function __construct(string $a)
    {
    }

    public static function isValid(string $value): bool
    {
        return true;
    }

    public function getValue(): string
    {
        return '';
    }

    public static function create(string $a): self
    {
        return new self($a);
    }
}
