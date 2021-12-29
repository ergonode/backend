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
use Ergonode\BatchAction\Domain\ValueObject\BatchActionStatus;

class DbalBatchActionMapperTest extends TestCase
{
    public function testMapping(): void
    {
        $id =  BatchActionId::generate();
        $type = new BatchActionType('test batch type');
        $payload = new \stdClass();
        $status = new BatchActionStatus(BatchActionStatus::PRECESSED);

        $batchAction = $this->createMock(BatchAction::class);
        $batchAction->method('getId')->willReturn($id);
        $batchAction->method('getType')->willReturn($type);
        $batchAction->method('getPayload')->willReturn($payload);
        $batchAction->method('isAutoEndOnErrors')->willReturn(true);
        $batchAction->method('getStatus')->willReturn($status);

        $mapper = new DbalBatchActionMapper();
        $result = $mapper->map($batchAction);

        self::assertArrayHasKey('id', $result);
        self::assertArrayHasKey('type', $result);
        self::assertEquals($id->getValue(), $result['id']);
        self::assertEquals($type->getValue(), $result['type']);
        self::assertEquals(serialize($payload), $result['payload']);
        self::assertEquals($status->getValue(), $result['status']);
        self::assertTrue($result['auto_end_on_errors']);
    }

    public function testCreation(): void
    {
        $status = new BatchActionStatus(BatchActionStatus::PRECESSED);

        $record['id'] = Uuid::uuid4()->toString();
        $record['type'] = 'test type';
        $record['status'] = $status->getValue();
        $record['payload'] = serialize(new \stdClass());
        $record['auto_end_on_errors'] = true;

        $mapper = new DbalBatchActionMapper();
        $result = $mapper->create($record);

        self::assertEquals($record['id'], $result->getId()->getValue());
        self::assertEquals($record['type'], $result->getType()->getValue());
        self::assertEquals($record['payload'], serialize($result->getPayload()));
        self::assertEquals($record['auto_end_on_errors'], $result->isAutoEndOnErrors());
        self::assertEquals($record['status'], $status->getValue());
    }
}
