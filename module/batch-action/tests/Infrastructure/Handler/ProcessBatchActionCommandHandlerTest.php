<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionCommand;
use Ergonode\BatchAction\Infrastructure\Handler\ProcessBatchActionCommandHandler;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Domain\Query\BatchActionQueryInterface;
use Ergonode\Core\Application\Messenger\DomainEventBus;
use Ergonode\BatchAction\Domain\Entity\BatchAction;

class ProcessBatchActionCommandHandlerTest extends TestCase
{
    private ProcessBatchActionCommand $command;
    private BatchActionRepositoryInterface $repository;
    private BatchActionQueryInterface $query;
    private DomainEventBus $bus;
    private BatchAction $batchAction;

    protected function setUp(): void
    {
        $this->command = $this->createMock(ProcessBatchActionCommand::class);
        $this->repository = $this->createMock(BatchActionRepositoryInterface::class);
        $this->query = $this->createMock(BatchActionQueryInterface::class);
        $this->bus = $this->createMock(DomainEventBus::class);
        $this->batchAction = $this->createMock(BatchAction::class);
    }

    public function testNoBatchAction(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $handler = new ProcessBatchActionCommandHandler($this->repository, $this->query, $this->bus);
        $handler($this->command);
    }

    public function testHasEntriesToProcess(): void
    {
        $this->query->method('hasEntriesToProcess')->willReturn(true);
        $this->repository->method('load')->willReturn($this->batchAction);
        $this->bus->expects(self::never())->method('dispatch');
        $handler = new ProcessBatchActionCommandHandler($this->repository, $this->query, $this->bus);
        $handler($this->command);
    }

    public function testSetAutoErrorsTrue(): void
    {
        $this->repository->method('load')->willReturn($this->batchAction);
        $this->batchAction->method('isAutoEndOnErrors')->willReturn(true);
        $this->query->expects(self::never())->method('hasErrors');
        $this->repository->expects(self::once())->method('save');
        $handler = new ProcessBatchActionCommandHandler($this->repository, $this->query, $this->bus);
        $this->bus->expects(self::once())->method('dispatch');

        $handler($this->command);
    }

    public function testSetAutoErrorsFalse(): void
    {
        $this->repository->method('load')->willReturn($this->batchAction);
        $this->batchAction->method('isAutoEndOnErrors')->willReturn(false);
        $this->query->expects(self::once())->method('hasErrors');
        $this->repository->expects(self::once())->method('save');
        $handler = new ProcessBatchActionCommandHandler($this->repository, $this->query, $this->bus);
        $this->bus->expects(self::once())->method('dispatch');

        $handler($this->command);
    }
}
