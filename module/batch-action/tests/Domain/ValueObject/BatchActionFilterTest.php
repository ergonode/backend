<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\ValueObject;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionIds;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BatchActionFilterTest extends TestCase
{
    /**
     * @var BatchActionIds|MockObject
     */
    private BatchActionIds $ids;

    private string $query;

    protected function setUp(): void
    {
        $this->ids = $this->createMock(BatchActionIds::class);
        $this->query = 'QUERY';
    }

    public function testCreation(): void
    {
        $object = new BatchActionFilter($this->ids, $this->query);

        self::assertEquals($this->ids, $object->getIds());
        self::assertEquals($this->query, $object->getQuery());
    }
}
