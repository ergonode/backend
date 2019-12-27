<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\ValueObject\OptionValue;

use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class MultilingualOptionTest extends TestCase
{
    /**
     */
    public function testValueCreation(): void
    {
        /** @var TranslatableString | MockObject $value */
        $value = new TranslatableString(['en' => 'english', 'pl' => 'polish']);

        $valueObject = new MultilingualOption($value);

        $this->assertSame($value, $valueObject->getValue());
        $this->assertSame(MultilingualOption::TYPE, $valueObject->getType());
        $this->assertSame('english,polish', (string) $valueObject);
        $this->assertTrue($valueObject->isMultilingual());
        $this->assertTrue($valueObject->equal($valueObject));
    }
}
