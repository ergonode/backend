<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Infrastructure\Persistence\Repository\Mapper\DbalBatchActionMapper;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DbalBatchActionMapperTest extends TestCase
{
    public function testMapping(): void
    {
        $id =  BatchActionId::generate();
        $type = new BatchActionType('test batch type');

        $batchAction = $this->createMock(BatchAction::class);
        $batchAction->method('getId')->willReturn($id);
        $batchAction->method('getType')->willReturn($type);

        $mapper = new DbalBatchActionMapper();
        $result = $mapper->map($batchAction);

        self::assertArrayHasKey('id', $result);
        self::assertArrayHasKey('type', $result);
        self::assertEquals($id->getValue(), $result['id']);
        self::assertEquals($type->getValue(), $result['type']);
    }

    public function testCreation(): void
    {
        $record['id'] = Uuid::uuid4()->toString();
        $record['type'] = 'test type';

        $mapper = new DbalBatchActionMapper();
        $result = $mapper->create($record);

        self::assertEquals($record['id'], $result->getId()->getValue());
        self::assertEquals($record['type'], $result->getType()->getValue());
    }
}
