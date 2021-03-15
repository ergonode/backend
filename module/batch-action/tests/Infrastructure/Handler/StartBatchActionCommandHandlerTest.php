<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use Ergonode\BatchAction\Infrastructure\Provider\BatchActionFilterIdsProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\BatchAction\Infrastructure\Handler\StartBatchActionCommandHandler;
use Ergonode\BatchAction\Domain\Command\StartBatchActionCommand;
use Ergonode\BatchAction\Domain\Entity\BatchAction;

class StartBatchActionCommandHandlerTest extends TestCase
{
    private BatchActionRepositoryInterface $repository;

    private StartBatchActionCommand $command;

    private BatchActionFilterIdsProvider $provider;

    private CommandBusInterface $messageBus;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(BatchActionRepositoryInterface::class);
        $this->command = $this->createMock(StartBatchActionCommand::class);
        $this->provider = $this->createMock(BatchActionFilterIdsProvider::class);
        $this->messageBus = $this->createMock(CommandBusInterface::class);
    }

    public function testCommandHandlingBatchActionNotExists(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->expects(self::once())->method('load')->willReturn(null);
        $handler = new StartBatchActionCommandHandler($this->repository, $this->provider, $this->messageBus);
        $handler->__invoke($this->command);
    }

    public function testCommandHandlingBatchActionExists(): void
    {
        $batchAction = $this->createMock(BatchAction::class);

        $this->repository->expects(self::once())->method('load')->willReturn($batchAction);
        $this->provider->expects(self::once())->method('provide');
        $handler = new StartBatchActionCommandHandler($this->repository, $this->provider, $this->messageBus);
        $handler->__invoke($this->command);
    }
}
