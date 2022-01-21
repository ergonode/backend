<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Infrastructure\Handler\Workflow\UpdateWorkflowTransitionCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowTransitionCommand;

class UpdateWorkflowTransitionCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $roleId = $this->createMock(RoleId::class);
        $command = $this->createMock(UpdateWorkflowTransitionCommand::class);
        $command->expects(self::once())->method('getRoleIds')->willReturn([$roleId]);
        $workflow = $this->createMock(Workflow::class);

        $repository = $this->createMock(WorkflowRepositoryInterface::class);
        $repository->expects(self::once())->method('load')->willReturn($workflow);
        $repository->expects(self::once())->method('save');

        $handler = new UpdateWorkflowTransitionCommandHandler($repository);
        $handler->__invoke($command);
    }
}
