<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Command\EndBatchActionProcessCommand;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Infrastructure\Handler\EndBatchActionProcessCommandHandler;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Bus\EventBusInterface;
use PHPUnit\Framework\TestCase;

class EndBatchActionProcessCommandHandlerTest extends TestCase
{
    private BatchActionRepositoryInterface $repository;

    private EventBusInterface $eventBus;

    private CommandBusInterface $commandBus;

    private EndBatchActionProcessCommand $command;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(BatchActionRepositoryInterface::class);
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);
        $this->command = $this->createMock(EndBatchActionProcessCommand::class);
    }

    public function testCommandHandlingProcessFinished(): void
    {
        $handler = new EndBatchActionProcessCommandHandler($this->repository, $this->eventBus, $this->commandBus);
        $this->repository->method('isProcessEnded')->willReturn(true);
        $this->eventBus->expects(self::once())->method('dispatch');
        $this->commandBus->expects(self::never())->method('dispatch');
        $handler->__invoke($this->command);
    }
    public function testCommandHandlingProcessNotFinished(): void
    {
        $handler = new EndBatchActionProcessCommandHandler($this->repository, $this->eventBus, $this->commandBus);
        $this->repository->method('isProcessEnded')->willReturn(false);
        $this->commandBus->expects(self::once())->method('dispatch');
        $this->eventBus->expects(self::never())->method('dispatch');
        $handler->__invoke($this->command);
    }
}
