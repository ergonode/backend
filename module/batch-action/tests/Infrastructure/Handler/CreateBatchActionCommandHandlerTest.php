<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use Ergonode\BatchAction\Infrastructure\Handler\CreateBatchActionCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Domain\Command\CreateBatchActionCommand;
use Ergonode\SharedKernel\Domain\AggregateId;

class CreateBatchActionCommandHandlerTest extends TestCase
{
    private BatchActionRepositoryInterface $repository;

    private CreateBatchActionCommand $command;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(BatchActionRepositoryInterface::class);
        $this->command = $this->createMock(CreateBatchActionCommand::class);
    }

    public function testCommandHandling(): void
    {
        $this->command->method('getIds')->willReturn([AggregateId::generate()]);
        $this->repository->expects(self::once())->method('save');
        $this->repository->expects(self::once())->method('addEntry');
        $handler = new CreateBatchActionCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
