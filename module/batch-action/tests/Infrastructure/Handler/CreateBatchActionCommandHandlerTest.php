<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use Ergonode\BatchAction\Infrastructure\Handler\CreateBatchActionCommandHandler;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionFilterIdsProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Domain\Command\CreateBatchActionCommand;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

class CreateBatchActionCommandHandlerTest extends TestCase
{
    private BatchActionRepositoryInterface $repository;

    private CreateBatchActionCommand $command;

    private CommandBusInterface $messageBus;

    private BatchActionFilterIdsProvider $provider;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(BatchActionRepositoryInterface::class);
        $this->command = $this->createMock(CreateBatchActionCommand::class);
        $this->messageBus = $this->createMock(CommandBusInterface::class);
        $this->provider = $this->createMock(BatchActionFilterIdsProvider::class);
    }

    public function testCommandHandling(): void
    {
        $this->repository->expects(self::once())->method('save');
        $handler = new CreateBatchActionCommandHandler($this->repository, $this->provider, $this->messageBus);
        $handler->__invoke($this->command);
    }
}
