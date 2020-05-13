<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Tests\Domain\ValueObject;

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

        $valueObject = new TranslatableStringValue($value);

        $this->assertSame($array, $valueObject->getValue());
        $this->assertSame(TranslatableStringValue::TYPE, $valueObject->getType());
    }
}
