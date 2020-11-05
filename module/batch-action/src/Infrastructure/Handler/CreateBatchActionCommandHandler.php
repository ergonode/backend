<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Command\CreateBatchActionCommand;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionEntryCommand;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

class CreateBatchActionCommandHandler
{
    private BatchActionRepositoryInterface $repository;

    private CommandBusInterface $commandBus;

    public function __construct(BatchActionRepositoryInterface $repository, CommandBusInterface $commandBus)
    {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    public function __invoke(CreateBatchActionCommand $command): void
    {
        $batchAction = new BatchAction($command->getId(), $command->getType(), $command->getAction());
        $this->repository->save($batchAction);

        foreach ($command->getIds() as $resourceId) {
            $batchActionId = $batchAction->getId();
            $this->repository->addEntry($batchActionId, $resourceId);
            $this->commandBus->dispatch(new ProcessBatchActionEntryCommand($batchActionId, $resourceId), true);
        }
    }
}
