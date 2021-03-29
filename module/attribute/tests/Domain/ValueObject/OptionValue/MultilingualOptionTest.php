<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\ValueObject\OptionValue;

use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

class MultilingualOptionTest extends TestCase
{
    public function testValueCreation(): void
    {
        $value = new TranslatableString(['en_GB' => 'english', 'pl_PL' => 'polish']);

        $valueObject = new MultilingualOption($value);

        self::assertSame($value, $valueObject->getValue());
        self::assertSame('english,polish', (string) $valueObject);
        self::assertTrue($valueObject->isMultilingual());
    }

    public function testEqualValue(): void
    {
        $value1 = new TranslatableString(['en_GB' => 'english', 'pl_PL' => 'polish']);
        $value2 = new TranslatableString(['en_GB' => 'english', 'pl_PL' => 'polish']);

        $valueObject1 = new MultilingualOption($value1);
        $valueObject2 = new MultilingualOption($value2);

        self::assertTrue($valueObject1->equal($valueObject2));
    }

    public function testNotEqualValue(): void
    {
        $value1 = new TranslatableString(['en_GB' => 'english', 'fr_FR' => 'franch']);
        $value2 = new TranslatableString(['en_GB' => 'english', 'pl_PL' => 'polish']);

        $valueObject1 = new MultilingualOption($value1);
        $valueObject2 = new MultilingualOption($value2);

        self::assertFalse($valueObject1->equal($valueObject2));
    }
}
