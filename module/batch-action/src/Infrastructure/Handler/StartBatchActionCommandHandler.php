<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Command\EndBatchActionCommand;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionEntryCommand;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionFilterIdsProvider;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\BatchAction\Domain\Command\StartBatchActionCommand;
use Webmozart\Assert\Assert;

class StartBatchActionCommandHandler
{
    private BatchActionRepositoryInterface $repository;

    private BatchActionFilterIdsProvider $filterProvider;

    private CommandBusInterface $commandBus;

    public function __construct(
        BatchActionRepositoryInterface $repository,
        BatchActionFilterIdsProvider $filterProvider,
        CommandBusInterface $commandBus
    ) {
        $this->repository = $repository;
        $this->filterProvider = $filterProvider;
        $this->commandBus = $commandBus;
    }

    public function __invoke(StartBatchActionCommand $command): void
    {
        $batchAction = $this->repository->load($command->getId());

        Assert::isInstanceOf($batchAction, BatchAction::class);

        $id = $batchAction->getId();
        $type = $batchAction->getType();

        $ids = $this->filterProvider->provide($type)->filter($command->getFilter());

        foreach ($ids as $resourceId) {
            $this->repository->addEntry($id, $resourceId);
            $entryCommand = new ProcessBatchActionEntryCommand($id, $type, $resourceId, $command->getPayload());
            $this->commandBus->dispatch($entryCommand, true);
        }

        $endCommand = new EndBatchActionCommand($id);
        $this->commandBus->dispatch($endCommand, true);
    }
}
