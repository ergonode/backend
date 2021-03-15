<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use PHPUnit\Framework\TestCase;

class StringCollectionValueTest extends TestCase
{
    public function testValueCreation(): void
    {
        $value = ['pl_PL' => 'value1', 'en_GB' => 'value2'];

        $valueObject1  = new StringCollectionValue($value);

        $valueObject2 = new StringCollectionValue($value);

        self::assertSame($value, $valueObject1->getValue());
        self::assertSame(StringCollectionValue::TYPE, $valueObject1->getType());
        self::assertSame('value1', $valueObject1->getTranslation(new Language('pl_PL')));
        self::assertSame("value1,value2", (string) $valueObject1);
        self::assertTrue($valueObject1->isEqual($valueObject2));
    }
}
