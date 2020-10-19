<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\ValueObject\OptionValue;

use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use PHPUnit\Framework\TestCase;

/**
 */
class StringOptionTest extends TestCase
{
    /**
     */
    public function testValueCreation(): void
    {
        $value = 'value';

        $valueObject = new StringOption($value);

        self::assertSame($value, $valueObject->getValue());
        self::assertSame(StringOption::TYPE, $valueObject->getType());
        self::assertSame($value, (string) $valueObject);
        self::assertFalse($valueObject->isMultilingual());
    }

    /**
     */
    public function testEqualValue(): void
    {
        $value1 = 'value1';
        $value2 = 'value1';

        $valueObject1 = new StringOption($value1);
        $valueObject2 = new StringOption($value2);

        self::assertTrue($valueObject1->equal($valueObject2));
    }

    /**
     */
    public function testNotEqualValue(): void
    {
        $value1 = 'value1';
        $value2 = 'value2';

        $valueObject1 = new StringOption($value1);
        $valueObject2 = new StringOption($value2);

        self::assertFalse($valueObject1->equal($valueObject2));
    }
}
