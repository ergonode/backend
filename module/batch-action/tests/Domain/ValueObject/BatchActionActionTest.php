<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\ValueObject;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionAction;
use PHPUnit\Framework\TestCase;

class BatchActionActionTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testValidCharactersValue(string $value): void
    {
        $valueObject = new BatchActionAction($value);
        self::assertEquals($value, $valueObject->getValue());
        self::assertEquals($value, (string) $valueObject);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidCharactersValue(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $valueObject = new BatchActionAction($value);
        self::assertEquals($value, $valueObject->getValue());
    }

    public function testObjectEqual(): void
    {
        $valueObject1 = new BatchActionAction('voa');
        $valueObject2 = new BatchActionAction('voa');

        self::assertTrue($valueObject1->isEqual($valueObject2));
        self::assertTrue($valueObject2->isEqual($valueObject1));
    }

    public function testNotObjectEqual(): void
    {
        $valueObject1 = new BatchActionAction('voa');
        $valueObject2 = new BatchActionAction('vob');

        self::assertFalse($valueObject1->isEqual($valueObject2));
        self::assertFalse($valueObject2->isEqual($valueObject1));
    }

    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['abcde'],
            ['aaaaaaaaaabbbbbbbbbb'], // max length
        ];
    }

    /**
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            [''],
            ['aaaaaaaaaabbbbbbbbbbcccccccccc'], // > max length
        ];
    }
}
