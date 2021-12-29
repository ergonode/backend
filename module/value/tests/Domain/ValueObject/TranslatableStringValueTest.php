<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use PHPUnit\Framework\TestCase;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class TranslatableStringValueTest extends TestCase
{
    public function testValueCreation(): void
    {
        $language1 = new Language('en_GB');
        $language2 = new Language('pl_PL');

        $value1 = 'english';
        $value2 = 'polish';

        $array = [$language1->getCode() => $value1, $language2->getCode() => $value2];

        $value = new TranslatableString($array);

        $valueObject1 = new TranslatableStringValue($value);
        $valueObject2 = new TranslatableStringValue($value);

        self::assertSame($array, $valueObject1->getValue());
        self::assertSame(TranslatableStringValue::TYPE, $valueObject1->getType());
        self::assertSame($value2, $valueObject1->getTranslation($language2));
        self::assertSame("english,polish", (string) $valueObject1);
        self::assertSame($value1, $valueObject1->getTranslation($language1));
        self::assertSame($value2, $valueObject1->getTranslation($language2));
        self::assertTrue($valueObject1->hasTranslation($language1));
        self::assertTrue($valueObject1->hasTranslation($language2));
        self::assertSame("$value1,$value2", (string) $valueObject1);
        self::assertTrue($valueObject1->isEqual($valueObject2));
    }

    public function testNotExistValueException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $language = new Language('en_GB');

        $value = new TranslatableStringValue(new TranslatableString());
        self::assertFalse($value->hasTranslation($language));
        $value->getTranslation($language);
    }

    public function testMergeInvalidValueObject(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $value1 = new StringValue('test');
        $value1->merge($this->createMock(ValueInterface::class));
    }

    /**
     * @dataProvider getData
     */
    public function testMerge(
        TranslatableStringValue $value1,
        TranslatableStringValue $value2,
        TranslatableStringValue $expected
    ): void {
        $result = $value1->merge($value2);

        $this->assertEquals($expected, $result);
    }

    public function getData(): array
    {
        return [
            [
                new TranslatableStringValue(new TranslatableString(['pl_PL' => 'polish'])),
                new TranslatableStringValue(new TranslatableString(['en_GB' => 'english'])),
                new  TranslatableStringValue(new TranslatableString(['en_GB' => 'english', 'pl_PL' => 'polish'])),
            ],
            [
                new TranslatableStringValue(new TranslatableString(['pl_PL' => 'polish'])),
                new TranslatableStringValue(new TranslatableString([])),
                new  TranslatableStringValue(new TranslatableString(['pl_PL' => 'polish'])),
            ],
            [
                new TranslatableStringValue(new TranslatableString([])),
                new TranslatableStringValue(new TranslatableString(['en_GB' => 'english'])),
                new  TranslatableStringValue(new TranslatableString(['en_GB' => 'english'])),
            ],
            [
                new TranslatableStringValue(new TranslatableString(['pl_PL' => 'polish1'])),
                new TranslatableStringValue(new TranslatableString(['en_GB' => 'english', 'pl_PL' => 'polish2'])),
                new  TranslatableStringValue(new TranslatableString(['en_GB' => 'english', 'pl_PL' => 'polish2'])),
            ],
        ];
    }
}
