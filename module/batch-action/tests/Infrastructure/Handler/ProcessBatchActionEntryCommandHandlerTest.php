<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionEntryCommand;
use Ergonode\BatchAction\Infrastructure\Handler\ProcessBatchActionEntryCommandHandler;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionProcessorProvider;

class ProcessBatchActionEntryCommandHandlerTest extends TestCase
{
    private BatchActionRepositoryInterface $batchActionRepository;

    private BatchActionProcessorProvider $processorProvider;


    private ProcessBatchActionEntryCommand $command;

    protected function setUp(): void
    {
        $this->batchActionRepository = $this->createMock(BatchActionRepositoryInterface::class);
        $this->batchActionRepository->expects(self::once())->method('markEntry');
        $this->processorProvider = $this->createMock(BatchActionProcessorProvider::class);
        $this->processorProvider->expects(self::once())->method('provide');

        $this->command = $this->createMock(ProcessBatchActionEntryCommand::class);
    }

    public function testCommandHandlingWithoutRelation(): void
    {
        $handler = new ProcessBatchActionEntryCommandHandler($this->processorProvider, $this->batchActionRepository);
        $handler->__invoke($this->command);
    }
}
