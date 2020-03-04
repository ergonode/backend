<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowTransitionCommand;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateWorkflowTransitionCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        /** @var WorkflowId $workflowId */
        $workflowId = $this->createMock(WorkflowId::class);

        /** @var StatusCode | MockObject $source */
        $source = $this->createMock(StatusCode::class);

        /** @var StatusCode | MockObject $destination */
        $destination = $this->createMock(StatusCode::class);

        /** @var RoleId[] | MockObject[] $roleIds */
        $roleIds = [$this->createMock(RoleId::class)];

        /** @var ConditionSetId | MockObject $conditionSetId */
        $conditionSetId = $this->createMock(ConditionSetId::class);

        $command = new UpdateWorkflowTransitionCommand(
            $workflowId,
            $source,
            $destination,
            $roleIds,
            $conditionSetId
        );

        $this->assertSame($workflowId, $command->getWorkflowId());
        $this->assertSame($source, $command->getSource());
        $this->assertSame($destination, $command->getDestination());
        $this->assertSame($roleIds, $command->getRoleIds());
        $this->assertSame($conditionSetId, $command->getConditionSetId());
    }
}
