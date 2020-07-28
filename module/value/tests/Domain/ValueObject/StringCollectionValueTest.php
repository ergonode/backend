<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Language;
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
        $value = ['pl' => 'value1', 'en' => 'value2'];

        $valueObject1  = new StringCollectionValue($value);

        $valueObject2 = new StringCollectionValue($value);

        $this->assertSame($value, $valueObject1->getValue());
        $this->assertSame(StringCollectionValue::TYPE, $valueObject1->getType());
        $this->assertSame('value1', $valueObject1->getTranslation(new Language('pl')));
        $this->assertSame("value1,value2", (string) $valueObject1);
        $this->assertTrue($valueObject1->isEqual($valueObject2));
    }
}
