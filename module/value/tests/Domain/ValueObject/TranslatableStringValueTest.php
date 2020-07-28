<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use PHPUnit\Framework\TestCase;

/**
 */
class TranslatableStringValueTest extends TestCase
{
    /**
     */
    public function testValueCreation(): void
    {
        $array = ['en' => 'english', 'pl_PL' => 'polish'];
        $value = new TranslatableString($array);

        $valueObject1 = new TranslatableStringValue($value);
        $valueObject2 = new TranslatableStringValue($value);

        $this->assertSame($array, $valueObject1->getValue());
        $this->assertSame(TranslatableStringValue::TYPE, $valueObject1->getType());
        $this->assertSame('polish', $valueObject1->getTranslation(new Language('pl_PL')));
        $this->assertSame("english,polish", (string) $valueObject1);
        $this->assertTrue($valueObject1->isEqual($valueObject2));
    }
}
