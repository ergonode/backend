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
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class StringCollectionValueTest extends TestCase
{
    public function testValueCreation(): void
    {
        $language1 = new Language('pl_PL');
        $language2 = new Language('en_GB');

        $value1 = 'value1';
        $value2 = 'value2';

        $value = [$language1->getCode() => $value1, $language2->getCode() => $value2];

        $valueObject1 = new StringCollectionValue($value);

        $valueObject2 = new StringCollectionValue($value);

        self::assertSame($value, $valueObject1->getValue());
        self::assertSame($value1, $valueObject1->getTranslation($language1));
        self::assertSame(StringCollectionValue::TYPE, $valueObject1->getType());
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

        $value = new StringCollectionValue([]);
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
        StringCollectionValue $value1,
        StringCollectionValue $value2,
        StringCollectionValue $expected
    ): void {

        $result = $value1->merge($value2);

        $this->assertEquals($expected, $result);
    }


    public function getData(): array
    {
        return [
            [
                new StringCollectionValue(['pl_PL' => 'polish']),
                new StringCollectionValue(['en_GB' => 'english']),
                new  StringCollectionValue(['en_GB' => 'english', 'pl_PL' => 'polish']),
            ],
            [
                new StringCollectionValue(['pl_PL' => 'polish']),
                new StringCollectionValue([]),
                new  StringCollectionValue(['pl_PL' => 'polish']),
            ],
            [
                new StringCollectionValue([]),
                new StringCollectionValue(['en_GB' => 'english']),
                new  StringCollectionValue(['en_GB' => 'english']),
            ],
            [
                new StringCollectionValue(['pl_PL' => 'polish1']),
                new StringCollectionValue(['en_GB' => 'english', 'pl_PL' => 'polish2']),
                new  StringCollectionValue(['en_GB' => 'english', 'pl_PL' => 'polish2']),
            ],
        ];
    }
}
