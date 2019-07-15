<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\ValueObject;

use Ergonode\Account\Domain\ValueObject\Password;
use PHPUnit\Framework\TestCase;

/**
 */
class PasswordTest extends TestCase
{
    /**
     * @param string $value
     *
     * @dataProvider validDataProvider
     */
    public function testValidaValue(string $value): void
    {
        $privilege = new Password($value);
        $this->assertEquals($value, $privilege->getValue());
        $this->assertNotNull($value, (string) $privilege);
    }

    /**
     * @param string
     *
     * @expectedException \InvalidArgumentException
     *
     * @dataProvider invalidDataProvider
     */
    public function testInvalidValue(string $value): void
    {
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
                'Any correct password'
            ],
            [
                // minimal length
                str_repeat('a', 6),
            ],
            [
                // maximal length
                str_repeat('a', 32),
            ]
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
                str_repeat('a', 33),
            ]
        ];
    }
}
