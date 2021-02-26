<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\ValueObject;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use PHPUnit\Framework\TestCase;

class CategoryCodeTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testCreationValidCode(string $code): void
    {
        $result = new CategoryCode($code);
        $this->assertSame($code, $result->getValue());
        $this->assertSame($code, (string) $result);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testCreationInValidCode(string $code): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new CategoryCode($code);
    }

    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['valida_code'],
            ['valida-code'],
            ['valida_code_12'],
            ['a'],
            ['a'],
            [str_repeat('a', 128)],
        ];
    }

    /**
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            ['aa&aa '],
            [' '],
            ['a a'],
            [' &'],
            ['!'],
            ['['],
            [str_repeat(' ', 255)],
            [PHP_EOL],
            [str_repeat('a', 256)],
        ];
    }
}
