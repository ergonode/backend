<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class DeleteWorkflowTransitionCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        /** @var WorkflowId| MockObject $workflowId */
        $workflowId = $this->createMock(WorkflowId::class);

        /** @var StatusId | MockObject $from */
        $from = $this->createMock(StatusId::class);

        /** @var StatusId | MockObject $to */
        $to = $this->createMock(StatusId::class);

        $command = new DeleteWorkflowTransitionCommand($workflowId, $from, $to);

        $this->assertSame($workflowId, $command->getWorkflowId());
        $this->assertSame($from, $command->getSource());
        $this->assertSame($to, $command->getDestination());
        $this->assertSame($from, $command->getFrom());
        $this->assertSame($to, $command->getTo());
    }
}
