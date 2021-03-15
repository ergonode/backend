<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionMessage;

class BatchActionMessageTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testValidCharactersValue(string $message, array $properties): void
    {
        $valueObject = new BatchActionMessage($message, $properties);
        self::assertEquals($message, $valueObject->getMessage());
        self::assertEquals($properties, $valueObject->getProperties());
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidCharactersValue(string $message, array $properties): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new BatchActionMessage($message, $properties);
    }

    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['abcde', ['a' => 'a'], ],
            ['a', []],
        ];
    }

    /**
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            ['', []],
            ['', ['a' => 'a']],
            ['a', ['' => 'a']],
            ['a', ['a' => '']],
            ['a', [0 => 'a']],
            ['a', ['a']],
        ];
    }
}
