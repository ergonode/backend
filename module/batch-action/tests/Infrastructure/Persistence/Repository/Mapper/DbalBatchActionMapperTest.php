<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionAction;
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
        $action = new BatchActionAction('test batch action');

        $batchAction = $this->createMock(BatchAction::class);
        $batchAction->method('getId')->willReturn($id);
        $batchAction->method('getType')->willReturn($type);
        $batchAction->method('getAction')->willReturn($action);

        $mapper = new DbalBatchActionMapper();
        $result = $mapper->map($batchAction);

        self::assertArrayHasKey('id', $result);
        self::assertArrayHasKey('resource_type', $result);
        self::assertArrayHasKey('action', $result);
        self::assertEquals($id->getValue(), $result['id']);
        self::assertEquals($type->getValue(), $result['resource_type']);
        self::assertEquals($action->getValue(), $result['action']);
    }

    public function testCreation(): void
    {
        $record['id'] = Uuid::uuid4()->toString();
        $record['resource_type'] = 'test resource_type';
        $record['action'] = 'test action';

        $mapper = new DbalBatchActionMapper();
        $result = $mapper->create($record);

        self::assertEquals($record['id'], $result->getId()->getValue());
        self::assertEquals($record['resource_type'], $result->getType()->getValue());
        self::assertEquals($record['action'], $result->getAction()->getValue());
    }
}
