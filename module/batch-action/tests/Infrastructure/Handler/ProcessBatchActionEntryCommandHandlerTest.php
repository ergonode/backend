<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Handler;

use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionEntryCommand;
use Ergonode\BatchAction\Infrastructure\Handler\ProcessBatchActionEntryCommandHandler;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionProcessorProvider;
use Ergonode\BatchAction\Domain\Entity\BatchAction;

class ProcessBatchActionEntryCommandHandlerTest extends TestCase
{
    private BatchActionRepositoryInterface $batchActionRepository;

    private BatchActionProcessorProvider $processorProvider;

    private ProcessBatchActionEntryCommand $command;

    protected function setUp(): void
    {
        $batchAction = $this->createMock(BatchAction::class);

        $this->batchActionRepository = $this->createMock(BatchActionRepositoryInterface::class);
        $this->batchActionRepository->expects(self::once())->method('load')->willReturn($batchAction);
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
