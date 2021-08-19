<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\ValueObject;

use Ergonode\Attribute\Domain\ValueObject\SystemAttributeCode;
use PHPUnit\Framework\TestCase;

class SystemAttributeCodeTest extends TestCase
{
    public function testValidCharactersValue(): void
    {
        $value = 'esa_test';
        $attributeCode = new SystemAttributeCode($value);
        $this->assertEquals($value, $attributeCode->getValue());
        $this->assertEquals($value, (string) $attributeCode);
    }

    public function testInvalidCharactersValue(): void
    {
        $value = 'abcd';
        $this->expectException(\InvalidArgumentException::class);
        $attributeCode = new SystemAttributeCode($value);
        $this->assertEquals($value, $attributeCode->getValue());
    }
}
