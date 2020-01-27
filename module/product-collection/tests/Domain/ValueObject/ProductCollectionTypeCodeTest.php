<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\ValueObject;

use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionTypeCodeTest extends TestCase
{
    /**
     * @param string $code
     *
     * @dataProvider validDataProvider
     */
    public function testCreationValidCode(string $code): void
    {
        $result = new ProductCollectionTypeCode($code);
        $this->assertSame($code, $result->getValue());
        $this->assertSame($code, (string) $result);
    }

    /**
     * @param string $code
     *
     * @dataProvider invalidDataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreationInValidCode(string $code): void
    {
        new ProductCollectionTypeCode($code);
    }

    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['valida_code'],
            ['valida-code'],
            ['valida code 12'],
            ['valida code'],
            ['a'],
            ['a'],
            [str_repeat('a', 255)],
        ];
    }

    /**
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            [''],
            [' '],
            [' a'],
            ['a '],
            [' &'],
            ['!'],
            ['['],
            [str_repeat(' ', 255)],
            [PHP_EOL],
            [str_repeat('a', 256)],
        ];
    }
}
