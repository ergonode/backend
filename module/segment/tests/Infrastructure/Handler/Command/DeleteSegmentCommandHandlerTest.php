<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Infrastructure\Handler\Command;

use Ergonode\Core\Infrastructure\Model\Relationship;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Segment\Domain\Command\DeleteSegmentCommand;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Infrastructure\Handler\Command\DeleteSegmentCommandHandler;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DeleteSegmentCommandHandlerTest extends TestCase
{
    /**
     * @var DeleteSegmentCommand|MockObject
     */
    private $command;

    /**
     * @var SegmentRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var RelationshipsResolverInterface|MockObject
     */
    private $resolver;

    /**
     * @var CommandBusInterface|MockObject
     */
    private $commandBus;

    protected function setUp(): void
    {
        $this->command = $this->createMock(DeleteSegmentCommand::class);
        $this->repository = $this->createMock(SegmentRepositoryInterface::class);
        $this->resolver = $this->createMock(RelationshipsResolverInterface::class);
        $this->commandBus = $this->createMock(CommandBusInterface::class);
    }

    /**
     * @throws \Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException
     */
    public function testCommandHandling(): void
    {
        $segment = $this->createMock(Segment::class);
        $segment->method('getConditionSetId')->willReturn(new ConditionSetId((string) Uuid::uuid4()));
        $this->resolver->method('resolve')->willReturn(null);
        $this->repository->expects($this->once())->method('load')->willReturn($segment);
        $this->commandBus->expects($this->once())->method('dispatch');
        $this->repository->expects($this->once())->method('delete');

        $handler = new DeleteSegmentCommandHandler($this->repository, $this->resolver, $this->commandBus);
        $handler->__invoke($this->command);
    }

    /**
     * @throws \Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException
     */
    public function testCommandHandlingWhenRelationOnConditionSet(): void
    {
        $segment = $this->createMock(Segment::class);
        $segment->method('getConditionSetId')->willReturn(new ConditionSetId((string) Uuid::uuid4()));
        $this->resolver->method('resolve')->willReturnOnConsecutiveCalls(
            null,
            $this->createMock(Relationship::class),
        );
        $this->repository->expects($this->once())->method('load')->willReturn($segment);
        $this->commandBus->expects($this->never())->method('dispatch');
        $this->repository->expects($this->once())->method('delete');

        $handler = new DeleteSegmentCommandHandler($this->repository, $this->resolver, $this->commandBus);
        $handler->__invoke($this->command);
    }

    /**
     * @throws \Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException
     */
    public function testCommandHandlingWhenNoConditionSetRelation(): void
    {
        $segment = $this->createMock(Segment::class);
        $segment->method('getConditionSetId')->willReturn(null);
        $this->resolver->method('resolve')->willReturn(null);
        $this->repository->expects($this->once())->method('load')->willReturn($segment);
        $this->commandBus->expects($this->never())->method('dispatch');
        $this->repository->expects($this->once())->method('delete');

        $handler = new DeleteSegmentCommandHandler($this->repository, $this->resolver, $this->commandBus);
        $handler->__invoke($this->command);
    }
}
