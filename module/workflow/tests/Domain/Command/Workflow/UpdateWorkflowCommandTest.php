<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\TestCase;

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
        /** @var StatusCode $status */
        $status = $this->createMock(StatusCode::class);

        $command = new UpdateWorkflowCommand($id, [$status]);
        $this->assertSame([$status], $command->getStatuses());
        $this->assertSame($id, $command->getId());
    }

    /**
     */
    public function testIncorrectStatusCode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        /** @var WorkflowId $id */
        $id = $this->createMock(WorkflowId::class);

        new UpdateWorkflowCommand($id, [new \stdClass()]);
    }
}
