<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Tests\Domain\ValueObject;

use Ergonode\Value\Domain\ValueObject\StringValue;
use PHPUnit\Framework\TestCase;

/**
 */
class StringValueTest extends TestCase
{
    /**
     */
    public function testValueCreation(): void
    {
        $value = 'string';

        $valueObject = new StringValue($value);

        $this->assertSame($value, $valueObject->getValue());
        $this->assertSame(StringValue::TYPE, $valueObject->getType());
        $this->assertSame($value, (string) $valueObject);
    }

    /**
     */
    public function testNullValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $value = '';

        $valueObject = new StringValue($value);
    }
}
