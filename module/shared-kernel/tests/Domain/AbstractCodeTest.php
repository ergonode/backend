<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Tests\Domain;

use Ergonode\SharedKernel\Domain\AbstractCode;
use PHPUnit\Framework\TestCase;

class AbstractCodeTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testValidCreation(string $string): void
    {
        $code = $this->getClass($string);
        self::assertEquals($string, $code->getValue());
        self::assertTrue(AbstractCode::isValid($string));
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testPositiveValidation(string $code): void
    {
        self::assertTrue(AbstractCode::isValid($code));
    }

    /**
     * @dataProvider inValidDataProvider
     */
    public function testNegativeValidation(string $code): void
    {
        self::assertFalse(AbstractCode::isValid($code));
    }

    /**
     * @dataProvider inValidDataProvider
     */
    public function testInvalidData(string $code): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->getClass($code);
    }

    /**
     * @dataProvider equalDataProvider
     */
    public function testEqual(string $string, string $value): void
    {
        $code1 = $this->getClass($string);
        $code2 = $this->getClass($value);

        self::assertTrue($code1->isEqual($code2));
    }

    /**
     * @dataProvider inEqualDataProvider
     */
    public function testInEqual(string $string, string $value): void
    {
        $code1 = $this->getClass($string);
        $code2 = $this->getClass($value);

        self::assertFalse($code1->isEqual($code2));
    }

    public function testString(): void
    {
        $string = 'test';
        $code = $this->getClass($string);
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
            [PHP_EOL],
            [str_repeat(' ', 129)],
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

    private function getClass(string $code): AbstractCode
    {
        return new class(
            $code,
        ) extends AbstractCode {
        };
    }
}
