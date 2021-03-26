<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Infrastructure\Handler\Command;

use Ergonode\Segment\Domain\Command\UpdateSegmentCommand;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Infrastructure\Handler\Command\UpdateSegmentCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class UpdateSegmentCommandHandlerTest extends TestCase
{
    private UpdateSegmentCommand $command;

    private SegmentRepositoryInterface $repository;

    private ApplicationEventBusInterface $eventBus;

    protected function setUp(): void
    {
        $this->command = $this->createMock(UpdateSegmentCommand::class);
        $this->repository = $this->createMock(SegmentRepositoryInterface::class);
        $this->eventBus = $this->createMock(ApplicationEventBusInterface::class);
    }

    public function testCommandHandlingExistsSegment(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn($this->createMock(Segment::class));
        $this->repository->expects($this->once())->method('save');

        $handler = new UpdateSegmentCommandHandler($this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }

    public function testCommandHandlingNotExistsSegment(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn(null);
        $this->repository->expects($this->never())->method('save');

        $this->expectException(\InvalidArgumentException::class);

        $handler = new UpdateSegmentCommandHandler($this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }
}
