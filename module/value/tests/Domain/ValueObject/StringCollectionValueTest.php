<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Tests\Domain\ValueObject;

use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use PHPUnit\Framework\TestCase;

/**
 */
class StringCollectionValueTest extends TestCase
{
    /**
     */
    public function testValueCreation(): void
    {
        $value = ['value1', 'value2'];

        $valueObject = new StringCollectionValue($value);

        $this->assertSame($value, $valueObject->getValue());
        $this->assertSame(StringCollectionValue::TYPE, $valueObject->getType());
        $this->assertSame('value1,value2', (string) $valueObject);
    }
}
