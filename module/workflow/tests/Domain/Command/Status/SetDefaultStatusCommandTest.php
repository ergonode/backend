<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Status;

use Ergonode\Workflow\Domain\Command\Status\SetDefaultStatusCommand;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

/**
 */
class SetDefaultStatusCommandTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var  WorkflowId | MockObject $workflowId */
        $workflowId = $this->createMock(WorkflowId::class);

        /** @var StatusId | MockObject $statusId */
        $statusId = $this->createMock(StatusId::class);

        $command = new SetDefaultStatusCommand($workflowId, $statusId);

        $this->assertSame($workflowId, $command->getWorkflowId());
        $this->assertSame($statusId, $command->getStatusId());
    }
}
