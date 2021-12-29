<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\ValueObject;

use Ergonode\Account\Domain\ValueObject\ResetToken;
use PHPUnit\Framework\TestCase;

class ResetTokenTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testValidCharactersValue(string $value): void
    {
        $valueObject = new ResetToken($value);
        self::assertEquals($value, $valueObject->getValue());
        self::assertEquals($value, (string) $valueObject);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidCharactersValue(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $valueObject = new ResetToken($value);
        self::assertEquals($value, $valueObject->getValue());
    }

    public function testObjectEqual(): void
    {
        $valueObject1 = new ResetToken('value');
        $valueObject2 = new ResetToken('value');

        self::assertTrue($valueObject1->isEqual($valueObject2));
        self::assertTrue($valueObject2->isEqual($valueObject1));
    }

    public function testObjectNotEqual(): void
    {
        $valueObject1 = new ResetToken('valueA');
        $valueObject2 = new ResetToken('valueB');

        self::assertFalse($valueObject1->isEqual($valueObject2));
        self::assertFalse($valueObject2->isEqual($valueObject1));
    }

    public function validDataProvider(): array
    {
        return [
            [
                'abcde',
            ],
            [
                'VARjGzbVDbGof2Wu9rUqZNvSEjieMnDqjOaBn14kyOGEw7gtJQofxqB9KaOh4qTCs2Ka5yXMi8JHp4GFFCtbML3PDVCwgNB6ohuo'.
                'NIiGBFlbKcKYwOLN9GiZXncEmW4a784ZT478BsOVg5i0SBtcGAMzh2UBsNn7WHsYvBlcEFWJGzAhKo52pcBf5JkniF9hD0GA1WoO'.
                'ATxsH88RoFE2kyHGquHqGqC7O2w8GyvY2WYebF0k1kSPnnKcxb4vwpn',
            ], // max length
        ];
    }

    public function invalidDataProvider(): array
    {
        return [
            [
                '',
            ],
            [
                'VARjGzbVDbGof2Wu9rUqZNvSEjieMnDqjOaBn14kyOGEw7gtJQofxqB9KaOh4qTCs2Ka5yXMi8JHp4GFFCtbML3PDVCwgNB6ohuo'.
                'NIiGBFlbKcKYwOLN9GiZXncEmW4a784ZT478BsOVg5i0SBtcGAMzh2UBsNn7WHsYvBlcEFWJGzAhKo52pcBf5JkniF9hD0GA1WoO'.
                'ATxsH88RoFE2kyHGquHqGqC7O2w8GyvY2WYebF0k1kSPnnKcxb4vwpnM',
            ], // max length
        ];
    }
}
