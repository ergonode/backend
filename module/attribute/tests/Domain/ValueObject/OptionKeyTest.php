<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Domain\ValueObject;

use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use PHPUnit\Framework\TestCase;

/**
 */
class OptionKeyTest extends TestCase
{
    /**
     * @param string $value
     *
     * @dataProvider validDataProvider
     */
    public function testValidCharactersValue(string $value): void
    {
        $key = new OptionKey($value);
        $this->assertEquals($value, $key->getValue());
        $this->assertEquals($value, (string) $key);
    }

    /**
     */
    public function testInvalidValueLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $value = str_repeat('a', 256);

        $key = new OptionKey($value);
        $this->assertEquals($value, $key->getValue());
    }

    /**
     * @return \Generator
     */
    public function validDataProvider(): \Generator
    {
        $collection = str_split('abcdefghijklmnopqrstuvwxyz1234567890_');
        foreach ($collection as $element) {
            yield ['abcd'.$element];
        }
    }
}
