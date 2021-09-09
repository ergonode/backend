<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Infrastructure\Handler\Workflow\AddWorkflowTransitionCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Command\Workflow\AddWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

class AddWorkflowTransitionCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $roleId = $this->createMock(RoleId::class);
        $conditionSetId = $this->createMock(ConditionSetId::class);
        $command = $this->createMock(AddWorkflowTransitionCommand::class);
        $command->expects(self::once())->method('getRoleIds')->willReturn([$roleId]);
        $command->expects(self::once())->method('getConditionSetId')->willReturn($conditionSetId);
        $workflow = $this->createMock(Workflow::class);

        $repository = $this->createMock(WorkflowRepositoryInterface::class);
        $repository->expects(self::once())->method('load')->willReturn($workflow);
        $repository->expects(self::once())->method('save');

        $handler = new AddWorkflowTransitionCommandHandler($repository);
        $handler->__invoke($command);
    }
}
