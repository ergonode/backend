<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\ValueObject;

use Ergonode\Account\Domain\ValueObject\Privilege;
use PHPUnit\Framework\TestCase;

/**
 */
class PrivilegeTest extends TestCase
{
    /**
     */
    public function testValidaValue(): void
    {
        $value = 'Any valid value';
        $privilege = new Privilege($value);
        self::assertEquals(strtoupper($value), $privilege->getValue());
        self::assertEquals('ANY VALID VALUE', $privilege->jsonSerialize());
        self::assertEquals('ANY VALID VALUE', (string) $privilege);
    }

    /**
     */
    public function testInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $value = str_repeat('To long Value', 100);
        new Privilege($value);
    }

    /**
     */
    public function testObjectEquality(): void
    {
        $value1 = 'value1';
        $value2 = 'value2';

        $privilege1 = new Privilege($value1);
        $privilege2 = new Privilege($value1);
        $privilege3 = new Privilege($value2);

        self::assertTrue($privilege1->isEqual($privilege2));
        self::assertTrue($privilege2->isEqual($privilege1));
        self::assertFalse($privilege1->isEqual($privilege3));
        self::assertFalse($privilege2->isEqual($privilege3));
        self::assertFalse($privilege3->isEqual($privilege1));
        self::assertFalse($privilege3->isEqual($privilege2));
    }
}
