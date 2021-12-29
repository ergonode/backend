<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Infrastructure\Handler\Workflow\DeleteWorkflowTransitionCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;

class DeleteWorkflowTransitionCommandHandlerTest extends TestCase
{
    /**
     * @var WorkflowRepositoryInterface|MockObject
     */
    private $mockRepository;

    private DeleteWorkflowTransitionCommandHandler $handler;

    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(WorkflowRepositoryInterface::class);

        $this->handler = new DeleteWorkflowTransitionCommandHandler($this->mockRepository);
    }

    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $command = $this->createMock(DeleteWorkflowTransitionCommand::class);

        $workflow = $this->createMock(Workflow::class);

        $this->mockRepository->expects(self::once())->method('load')->willReturn($workflow);
        $workflow->expects(self::once())->method('removeTransition');
        $this->mockRepository->expects(self::once())->method('save');

        $this->handler->__invoke($command);
    }

    /**
     * @throws \Exception
     */
    public function testCommandHandlingWorkflowNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = $this->createMock(DeleteWorkflowTransitionCommand::class);

        $this->mockRepository->expects(self::once())->method('load')->willReturn(null);

        $this->handler->__invoke($command);
    }
}
