<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\ValueObject;

use Ergonode\Account\Domain\ValueObject\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testValidaValue(string $value): void
    {
        $password = new Password($value);
        $this->assertEquals($value, $password->getValue());
        $this->assertEquals($value, (string) $password);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidValue(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Password($value);
    }

    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            [
                // minimal length
                'Any correct password',
            ],
            [
                // minimal length
                str_repeat('a', 6),
            ],
            [
                // maximal length
                str_repeat('a', 32),
            ],
        ];
    }

    /**
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            [
                // to short value
                str_repeat('a', 5),
            ],
            [
                // to long value
                str_repeat('a', 64),
            ],
        ];
    }
}
