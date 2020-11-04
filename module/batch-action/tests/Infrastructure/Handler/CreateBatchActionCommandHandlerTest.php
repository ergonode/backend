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
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;

class CreateBatchActionCommandHandlerTest extends TestCase
{
    private BatchActionRepositoryInterface $repository;

    private CreateBatchActionCommand $command;

    private MessageBusInterface $messageBus;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(BatchActionRepositoryInterface::class);
        $this->command = $this->createMock(CreateBatchActionCommand::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
    }

    public function testCommandHandling(): void
    {
        $mockEnvelope = new Envelope(new \stdClass());
        $this->command->method('getIds')->willReturn([AggregateId::generate()]);
        $this->repository->expects(self::once())->method('save');
        $this->repository->expects(self::once())->method('addEntry');
        $this->messageBus->expects(self::once())->method('dispatch')->willReturn($mockEnvelope);
        $handler = new CreateBatchActionCommandHandler($this->repository, $this->messageBus);
        $handler->__invoke($this->command);
    }
}
