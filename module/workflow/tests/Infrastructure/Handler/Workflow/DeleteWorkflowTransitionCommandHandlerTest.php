<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Infrastructure\Handler\Workflow\DeleteWorkflowTransitionCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;

/**
 */
class DeleteWorkflowTransitionCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $command = $this->createMock(DeleteWorkflowTransitionCommand::class);

        $workflow = $this->createMock(Workflow::class);

        $repository = $this->createMock(WorkflowRepositoryInterface::class);
        $repository->expects(self::once())->method('load')->willReturn($workflow);
        $repository->expects(self::once())->method('save');

        $handler = new DeleteWorkflowTransitionCommandHandler($repository);
        $handler->__invoke($command);
    }
}
