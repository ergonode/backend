<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Tests\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\ValueObject\Code;
use PHPUnit\Framework\TestCase;

class CodeTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testValidCreation(string $string): void
    {
        $code = new Code($string);
        self::assertEquals($string, $code->getValue());
        self::assertTrue(Code::isValid($string));
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testPositiveValidation(string $code): void
    {
        self::assertTrue(Code::isValid($code));
    }

    /**
     * @dataProvider inValidDataProvider
     */
    public function testNegativeValidation(string $code): void
    {
        self::assertFalse(Code::isValid($code));
    }

    /**
     * @dataProvider inValidDataProvider
     */
    public function testInvalidData(string $code): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Code($code);
    }

    /**
     * @dataProvider equalDataProvider
     */
    public function testEqual(string $string, string $value): void
    {
        $code1 = new Code($string);
        $code2 = new Code($value);

        self::assertTrue($code1->isEqual($code2));
    }

    /**
     * @dataProvider inEqualDataProvider
     */
    public function testInEqual(string $string, string $value): void
    {
        $code1 = new Code($string);
        $code2 = new Code($value);

        self::assertFalse($code1->isEqual($code2));
    }

    public function testString(): void
    {
        $string = 'test';
        $code = new Code($string);
        self::assertSame($code->__toString(), $string);
    }

    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['valid code'],
            [str_repeat('a', 128)],

        ];
    }

    /**
     * @return array
     */
    public function inValidDataProvider(): array
    {
        return [
            [''],
            [str_repeat('a', 129)],
        ];
    }

    public function equalDataProvider(): array
    {
        return [
            ['equal', 'equal'],
            [str_repeat('a', 128), str_repeat('a', 128)],
        ];
    }

    public function inEqualDataProvider(): array
    {
        return [
            ['equal', 'no equal'],
            [str_repeat('a', 128), str_repeat('b', 128)],
        ];
    }
}
