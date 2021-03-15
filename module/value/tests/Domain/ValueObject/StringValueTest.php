<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Tests\Domain\ValueObject;

use Ergonode\Value\Domain\ValueObject\StringValue;
use PHPUnit\Framework\TestCase;

class StringValueTest extends TestCase
{
    public function testValueCreation(): void
    {
        $value = 'string';

        $valueObject1 = new StringValue($value);
        $valueObject2 = new StringValue($value);

        $this->assertSame($value, $valueObject1->getValue()[null]);
        $this->assertSame(StringValue::TYPE, $valueObject1->getType());
        $this->assertSame($value, (string) $valueObject1);
        $this->assertTrue($valueObject1->isEqual($valueObject2));
    }

    public function testNullValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $value = '';

        new StringValue($value);
    }
}
