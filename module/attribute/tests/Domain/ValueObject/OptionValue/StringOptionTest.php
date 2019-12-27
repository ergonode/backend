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

        $this->assertSame($value, $valueObject->getValue());
        $this->assertSame(StringOption::TYPE, $valueObject->getType());
        $this->assertSame($value, (string) $valueObject);
        $this->assertTrue($valueObject->equal($valueObject));
        $this->assertFalse($valueObject->isMultilingual());
    }
}
