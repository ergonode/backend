<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Domain\ValueObject;

use Ergonode\Attribute\Domain\ValueObject\AttributeValueType;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeValueTypeTest extends TestCase
{
    /**
     * @param string $value
     *
     * @dataProvider validDataProvider
     */
    public function testCorrectValues(string $value): void
    {
        $valueType = new AttributeValueType($value);
        $this->assertEquals($value, $valueType->getValue());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIncorrectValue(): void
    {
        new AttributeValueType('Any incorrect value');
    }

    /**
     * @return \Generator
     */
    public function validDataProvider(): \Generator
    {
        foreach (AttributeValueType::AVAILABLE as $value) {
            yield [$value];
        }
    }
}
