<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Command;

use Ergonode\BatchAction\Domain\Command\ProcessBatchActionEntryCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;

class ProcessBatchActionEntryCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $batchActionId = $this->createMock(BatchActionId::class);
        $resourceId = $this->createMock(AggregateId::class);

        $command = new ProcessBatchActionEntryCommand($batchActionId, $resourceId);

        self::assertEquals($batchActionId, $command->getId());
        self::assertEquals($resourceId, $command->getResourceId());
    }
}
