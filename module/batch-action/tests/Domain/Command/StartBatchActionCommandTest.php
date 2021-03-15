<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Command;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\BatchAction\Domain\Command\StartBatchActionCommand;

class StartBatchActionCommandTest extends TestCase
{
    private BatchActionId $id;

    private BatchActionFilterInterface $filter;

    protected function setUp(): void
    {
        $this->id = $this->createMock(BatchActionId::class);
        $this->filter = $this->createMock(BatchActionFilterInterface::class);
    }

    public function testCreation(): void
    {
        $payload = [$this->createMock(AggregateId::class)];

        $command = new StartBatchActionCommand($this->id, $this->filter, $payload);

        self::assertEquals($this->id, $command->getId());
        self::assertEquals($this->filter, $command->getFilter());
        self::assertEquals($payload, $command->getPayload());
    }
}
