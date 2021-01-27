<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Handler\Workflow;

use Ergonode\Core\Infrastructure\Model\Relationship;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\Workflow\Infrastructure\Handler\Workflow\DeleteWorkflowTransitionCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;
use Ramsey\Uuid\Uuid;

class DeleteWorkflowTransitionCommandHandlerTest extends TestCase
{
    /**
     * @var WorkflowRepositoryInterface|MockObject
     */
    private $mockRepository;

    /**
     * @var RelationshipsResolverInterface|MockObject
     */
    private $mockRelationshipResolver;

    /**
     * @var CommandBusInterface|MockObject
     */
    private $mockCommandBus;

    private DeleteWorkflowTransitionCommandHandler $handler;

    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(WorkflowRepositoryInterface::class);
        $this->mockRelationshipResolver = $this->createMock(RelationshipsResolverInterface::class);
        $this->mockCommandBus = $this->createMock(CommandBusInterface::class);

        $this->handler = new DeleteWorkflowTransitionCommandHandler(
            $this->mockRepository,
            $this->mockRelationshipResolver,
            $this->mockCommandBus,
        );
    }

    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $command = $this->createMock(DeleteWorkflowTransitionCommand::class);

        $workflow = $this->createMock(Workflow::class);

        $this->mockRepository->expects(self::once())->method('load')->willReturn($workflow);
        $this->mockCommandBus->expects($this->never())->method('dispatch');
        $this->mockRepository->expects(self::once())->method('save');

        $this->handler->__invoke($command);
    }

    public function testCommandHandlingWhenRelationOnConditionSet(): void
    {
        $command = $this->createMock(DeleteWorkflowTransitionCommand::class);
        $workflow = $this->createMock(Workflow::class);
        $transition = $this->createMock(Transition::class);

        $workflow->method('getTransition')->willReturn($transition);
        $transition->method('getConditionSetId')->willReturn(new ConditionSetId((string) Uuid::uuid4()));
        $this->mockRelationshipResolver->method('resolve')->willReturn(
            $this->createMock(Relationship::class),
        );
        $this->mockRepository->expects($this->once())->method('load')->willReturn($workflow);
        $this->mockCommandBus->expects($this->never())->method('dispatch');
        $this->mockRepository->expects($this->once())->method('save');

        $this->handler->__invoke($command);
    }

    public function testCommandHandlingWhenRelationWithConditionSet(): void
    {
        $command = $this->createMock(DeleteWorkflowTransitionCommand::class);
        $workflow = $this->createMock(Workflow::class);
        $transition = $this->createMock(Transition::class);

        $workflow->method('getTransition')->willReturn($transition);
        $transition->method('getConditionSetId')->willReturn(new ConditionSetId((string) Uuid::uuid4()));
        $this->mockRelationshipResolver->method('resolve')->willReturn(null);
        $this->mockRepository->expects($this->once())->method('load')->willReturn($workflow);
        $this->mockCommandBus->expects($this->once())->method('dispatch');
        $this->mockRepository->expects($this->once())->method('save');

        $this->handler->__invoke($command);
    }
}
