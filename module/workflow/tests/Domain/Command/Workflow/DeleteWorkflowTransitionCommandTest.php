<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteWorkflowTransitionCommandTest extends TestCase
{
    /**
     */
    public function testCommandCreation(): void
    {
        /** @var WorkflowId| MockObject $workflowId */
        $workflowId = $this->createMock(WorkflowId::class);

        /** @var StatusCode | MockObject $source */
        $source = $this->createMock(StatusCode::class);

        /** @var StatusCode | MockObject $destination */
        $destination = $this->createMock(StatusCode::class);

        $command = new DeleteWorkflowTransitionCommand($workflowId, $source, $destination);

        $this->assertSame($workflowId, $command->getWorkflowId());
        $this->assertSame($source, $command->getSource());
        $this->assertSame($destination, $command->getDestination());
    }
}
