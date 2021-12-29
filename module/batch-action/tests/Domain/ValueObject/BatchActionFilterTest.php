<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\ValueObject;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionIds;
use PHPUnit\Framework\TestCase;

class BatchActionFilterTest extends TestCase
{
    /**
     * @dataProvider argumentsProvider
     */
    public function testCreation(?BatchActionIds $ids, ?string $query): void
    {
        $object = new BatchActionFilter($ids, $query);

        self::assertEquals($ids, $object->getIds());
        self::assertEquals($query, $object->getQuery());
    }

    public function argumentsProvider(): array
    {
        return [
            [$this->createMock(BatchActionIds::class), 'query'],
            [$this->createMock(BatchActionIds::class), null],
            [null, 'query'],
        ];
    }

    public function testThrowsExceptionOnNullArugments(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new BatchActionFilter(null, null);
    }
}
