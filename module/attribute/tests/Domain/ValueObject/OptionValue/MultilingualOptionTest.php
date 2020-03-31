<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\ValueObject\OptionValue;

use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class MultilingualOptionTest extends TestCase
{
    /**
     */
    public function testValueCreation(): void
    {
        $value = new TranslatableString(['en' => 'english', 'pl-PL' => 'polish']);

        $valueObject = new MultilingualOption($value);

        $this->assertSame($value, $valueObject->getValue());
        $this->assertSame(MultilingualOption::TYPE, $valueObject->getType());
        $this->assertSame('english,polish', (string) $valueObject);
        $this->assertTrue($valueObject->isMultilingual());
    }

    /**
     */
    public function testEqualValue(): void
    {
        $value1 = new TranslatableString(['en' => 'english', 'pl-PL' => 'polish']);
        $value2 = new TranslatableString(['en' => 'english', 'pl-PL' => 'polish']);

        $valueObject1 = new MultilingualOption($value1);
        $valueObject2 = new MultilingualOption($value2);

        $this->assertTrue($valueObject1->equal($valueObject2));
    }

    /**
     */
    public function testNotEqualValue(): void
    {
        $value1 = new TranslatableString(['en' => 'english', 'fr' => 'franch']);
        $value2 = new TranslatableString(['en' => 'english', 'pl-PL' => 'polish']);

        $valueObject1 = new MultilingualOption($value1);
        $valueObject2 = new MultilingualOption($value2);

        $this->assertFalse($valueObject1->equal($valueObject2));
    }
}
