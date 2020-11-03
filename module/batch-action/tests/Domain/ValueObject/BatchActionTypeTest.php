<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\ValueObject;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use PHPUnit\Framework\TestCase;

class BatchActionTypeTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testValidCharactersValue(string $value): void
    {
        $valueObject = new BatchActionType($value);
        $this::assertEquals($value, $valueObject->getValue());
        $this::assertEquals($value, (string) $valueObject);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidCharactersValue(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $valueObject = new BatchActionType($value);
        $this::assertEquals($value, $valueObject->getValue());
    }

    public function testObjectEqual(): void
    {
        $valueObject1 = new BatchActionType('voa');
        $valueObject2 = new BatchActionType('voa');

        $this::assertTrue($valueObject1->isEqual($valueObject2));
        $this::assertTrue($valueObject2->isEqual($valueObject1));
    }

    public function testNotObjectEqual(): void
    {
        $valueObject1 = new BatchActionType('voa');
        $valueObject2 = new BatchActionType('vob');

        $this::assertFalse($valueObject1->isEqual($valueObject2));
        $this::assertFalse($valueObject2->isEqual($valueObject1));
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
