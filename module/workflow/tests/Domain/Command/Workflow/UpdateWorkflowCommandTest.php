<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

/**
 */
class UpdateWorkflowCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        /** @var WorkflowId $id */
        $id = $this->createMock(WorkflowId::class);
        /** @var StatusId $status */
        $status = $this->createMock(StatusId::class);

        $command = new UpdateWorkflowCommand($id, [$status]);
        $this->assertSame([$status], $command->getStatuses());
        $this->assertSame($id, $command->getId());
    }

    /**
     */
    public function testIncorrectStatusId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        /** @var WorkflowId $id */
        $id = $this->createMock(WorkflowId::class);

        new UpdateWorkflowCommand($id, [new \stdClass()]);
    }
}
