<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Command;

use Ergonode\BatchAction\Domain\Command\CreateBatchActionCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionAction;
use Ergonode\SharedKernel\Domain\AggregateId;

class CreateBatchActionCommandTest extends TestCase
{
    private BatchActionId $id;

    private BatchActionType $type;

    private BatchActionAction $action;

    protected function setUp(): void
    {
        $this->id = $this->createMock(BatchActionId::class);
        $this->type = $this->createMock(BatchActionType::class);
        $this->action = $this->createMock(BatchActionAction::class);
    }

    public function testCreation(): void
    {
        $ids = [$this->createMock(AggregateId::class)];

        $command = new CreateBatchActionCommand($this->id, $this->type, $this->action, $ids);

        self::assertEquals($this->id, $command->getId());
        self::assertEquals($this->type, $command->getType());
        self::assertEquals($this->action, $command->getAction());
        self::assertEquals($ids, $command->getIds());
    }

    public function testCreationEmptyIds(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new CreateBatchActionCommand($this->id, $this->type, $this->action, []);
    }

    public function testCreationIdsClassType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new CreateBatchActionCommand($this->id, $this->type, $this->action, [new \stdClass()]);
    }
}
