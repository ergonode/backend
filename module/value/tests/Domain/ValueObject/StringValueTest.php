<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Tests\Domain\ValueObject;

use Ergonode\Value\Domain\ValueObject\StringValue;
use PHPUnit\Framework\TestCase;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

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

    public function testMergeInvalidValueObject(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $value1 = new StringValue('test');
        $value1->merge($this->createMock(ValueInterface::class));
    }

    /**
     * @dataProvider getData
     */
    public function testMerge(StringValue $value1, StringValue $value2, StringValue $expected): void
    {
        $result = $value1->merge($value2);

        $this->assertEquals($expected, $result);
    }

    public function getData(): array
    {
        return [
            [new StringValue('test1'), new StringValue('test2'), new StringValue('test2')],
            [new StringValue('test2'), new StringValue('test1'), new StringValue('test1')],
        ];
    }
}
