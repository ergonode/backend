<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Command;

use Ergonode\BatchAction\Domain\Command\CreateBatchActionCommand;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\SharedKernel\Domain\AggregateId;

class CreateBatchActionCommandTest extends TestCase
{
    private BatchActionId $id;

    private BatchActionType $type;

    private BatchActionFilter $filter;

    protected function setUp(): void
    {
        $this->id = $this->createMock(BatchActionId::class);
        $this->type = $this->createMock(BatchActionType::class);
        $this->filter = $this->createMock(BatchActionFilter::class);
    }

    public function testCreation(): void
    {
        $payload = [$this->createMock(AggregateId::class)];

        $command = new CreateBatchActionCommand($this->id, $this->type, $this->filter, $payload);

        self::assertEquals($this->id, $command->getId());
        self::assertEquals($this->type, $command->getType());
        self::assertEquals($this->filter, $command->getFilter());
        self::assertEquals($payload, $command->getPayload());
    }
}
