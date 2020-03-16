<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\ValueObject;

use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeGroupCodeTest extends TestCase
{
    /**
     */
    public function testValueCreation(): void
    {
        $value = 'vaLue ';
        $check = 'value';

        $valueObject = new AttributeGroupCode($value);

        $this->assertSame($check, $valueObject->getValue());
        $this->assertSame($check, (string) $valueObject);
    }

    /**
     */
    public function testInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $value = 'f//.,ef';

        $valueObject = new AttributeGroupCode($value);
    }
}
