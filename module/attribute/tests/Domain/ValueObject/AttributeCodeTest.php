<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\ValueObject;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use PHPUnit\Framework\TestCase;

class AttributeCodeTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testValidCharactersValue(string $value): void
    {
        $attributeCode = new AttributeCode($value);
        $this->assertEquals($value, $attributeCode->getValue());
        $this->assertEquals($value, (string) $attributeCode);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidCharactersValue(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $attributeCode = new AttributeCode($value);
        $this->assertEquals($value, $attributeCode->getValue());
    }

    public function validDataProvider(): \Generator
    {
        $collection = str_split('abcdefghijklmnopqrstuvwxyz1234567890_');
        foreach ($collection as $element) {
            yield ['abcd'.$element];
        }
    }

    public function invalidDataProvider(): \Generator
    {
        $collection = str_split('@#$%^&*()+={}[]:;"/?,.<>~`');
        foreach ($collection as $element) {
            yield ['abcd'.$element];
        }
    }
}
