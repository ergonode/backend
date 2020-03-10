<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Infrastructure\Handler\Command;

use Ergonode\Segment\Domain\Command\UpdateSegmentCommand;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Infrastructure\Handler\Command\UpdateSegmentCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateSegmentCommandHandlerTest extends TestCase
{
    /**
     * @var UpdateSegmentCommand|MockObject
     */
    private $command;

    /**
     * @var SegmentRepositoryInterface|MockObject
     */
    private $repository;

    /**
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(UpdateSegmentCommand::class);
        $this->repository = $this->createMock(SegmentRepositoryInterface::class);
    }

    /**
     */
    public function testCommandHandlingExistsSegment(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn($this->createMock(Segment::class));
        $this->repository->expects($this->once())->method('save');

        $handler = new UpdateSegmentCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }

    /**
     */
    public function testCommandHandlingNotExistsSegment(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn(null);
        $this->repository->expects($this->never())->method('save');

        $this->expectException(\InvalidArgumentException::class);

        $handler = new UpdateSegmentCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
