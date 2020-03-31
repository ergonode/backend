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
        $value = new TranslatableString(['en' => 'english', 'pl-PL' => 'polish']);

        $valueObject = new TranslatableStringValue($value);

        $this->assertSame($value, $valueObject->getValue());
        $this->assertSame(TranslatableStringValue::TYPE, $valueObject->getType());
        $this->assertSame('english,polish', (string) $valueObject);
    }
}
