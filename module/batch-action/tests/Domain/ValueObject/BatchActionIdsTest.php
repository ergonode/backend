<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\ValueObject;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionIds;
use Ergonode\SharedKernel\Domain\AggregateId;
use PHPUnit\Framework\TestCase;

class BatchActionIdsTest extends TestCase
{
    public function testCreation(): void
    {
        $list = [$this->createMock(AggregateId::class)];
        $include = false;

        $object = new BatchActionIds($list, $include);

        self::assertEquals($list, $object->getList());
        self::assertEquals($include, $object->isIncluded());
    }

    public function testCreationEmptyIds(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new BatchActionIds([], true);
    }

    public function testCreationIdsClassType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new BatchActionIds([new \stdClass()], true);
    }
}
