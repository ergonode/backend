<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Infrastructure\Handler\Command;

use Ergonode\Segment\Domain\Command\CreateSegmentCommand;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Infrastructure\Handler\Command\CreateSegmentCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class CreateSegmentCommandHandlerTest extends TestCase
{
    private CreateSegmentCommand $command;

    private SegmentRepositoryInterface $repository;

    private ApplicationEventBusInterface $eventBus;

    protected function setUp(): void
    {
        $this->command = $this->createMock(CreateSegmentCommand::class);
        $this->repository = $this->createMock(SegmentRepositoryInterface::class);
        $this->eventBus = $this->createMock(ApplicationEventBusInterface::class);
    }

    public function testCommandHandling(): void
    {
        $this->repository->expects($this->once())->method('save');

        $handler = new CreateSegmentCommandHandler($this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }
}
