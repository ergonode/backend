<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\ValueObject\OptionValue;

use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use PHPUnit\Framework\TestCase;

class StringOptionTest extends TestCase
{
    public function testValueCreation(): void
    {
        $value = 'value';

        $valueObject = new StringOption($value);

        $this->assertSame($value, $valueObject->getValue());
        $this->assertSame($value, (string) $valueObject);
        $this->assertFalse($valueObject->isMultilingual());
    }

    public function testEqualValue(): void
    {
        $value1 = 'value1';
        $value2 = 'value1';

        $valueObject1 = new StringOption($value1);
        $valueObject2 = new StringOption($value2);

        $this->assertTrue($valueObject1->equal($valueObject2));
    }

    public function testNotEqualValue(): void
    {
        $value1 = 'value1';
        $value2 = 'value2';

        $valueObject1 = new StringOption($value1);
        $valueObject2 = new StringOption($value2);

        $this->assertFalse($valueObject1->equal($valueObject2));
    }
}
