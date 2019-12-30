<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\ValueObject;

use Ergonode\Attribute\Domain\ValueObject\Unit;
use PHPUnit\Framework\TestCase;

/**
 */
class UnitTest extends TestCase
{
    /**
     */
    public function testValueCreation(): void
    {
        $code = ' code ';
        $check = 'CODE';

        $valueObject = new Unit($code);

        $this->assertSame($check, $valueObject->getCode());
        $this->assertSame($check, (string) $valueObject);
    }

    /**
     */
    public function testFromStringCreation(): void
    {
        $code = ' code ';
        $check = 'CODE';

        $this->assertSame($check, Unit::fromString($code)->getCode());
    }
}
