<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Status;

use Ergonode\Workflow\Domain\Command\Status\SetDefaultStatusCommand;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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

        /** @var StatusCode | MockObject $statusCode */
        $statusCode = $this->createMock(StatusCode::class);

        $command = new SetDefaultStatusCommand($workflowId, $statusCode);

        $this->assertSame($workflowId, $command->getWorkflowId());
        $this->assertSame($statusCode, $command->getStatusCode());
    }
}
