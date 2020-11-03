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

        $this::assertArrayHasKey('id', $result);
        $this::assertArrayHasKey('resource_type', $result);
        $this::assertArrayHasKey('action', $result);
        $this::assertEquals($id->getValue(), $result['id']);
        $this::assertEquals($type->getValue(), $result['resource_type']);
        $this::assertEquals($action->getValue(), $result['action']);
    }
}
