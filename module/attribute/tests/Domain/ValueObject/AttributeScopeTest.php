<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

/**
 */
class AttributeScopeTest extends TestCase
{
    /**
     * @param string $value
     *
     * @dataProvider validDataProvider
     */
    public function testValidCharactersValue(string $value): void
    {
        $attributeScope = new AttributeScope($value);
        $this->assertEquals($value, $attributeScope->getValue());
        $this->assertEquals($value, (string) $attributeScope);
    }

    /**
     * @param string $value
     *
     * @dataProvider invalidDataProvider
     *
     */
    public function testInvalidCharactersValue(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $attributeScope = new AttributeScope($value);
        $this->assertEquals($value, $attributeScope->getValue());
    }

    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['local'],
            ['global'],
        ];
    }

    /**
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
           [''],
           ['invalid'],
        ];
    }
}
