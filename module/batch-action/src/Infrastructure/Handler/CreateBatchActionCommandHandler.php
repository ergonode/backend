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
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

class CreateBatchActionCommandHandler
{
    private BatchActionRepositoryInterface $repository;

    private CommandBusInterface $commandBus;

    public function __construct(
        BatchActionRepositoryInterface $repository,
        CommandBusInterface $commandBus
    ) {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    public function __invoke(CreateBatchActionCommand $command): void
    {
        $type = $command->getType();
        $id = $command->getId();
        $batchAction = new BatchAction($id, $type);
        $this->repository->save($batchAction);

        if ($command->getFilter() && $command->getFilter()->getIds()) {
            foreach ($command->getFilter()->getIds()->getList() as $resourceId) {
                $this->repository->addEntry($id, $resourceId);
                $entryCommand = new ProcessBatchActionEntryCommand($id, $type, $resourceId);
                $this->commandBus->dispatch($entryCommand, true);
            }
        }
    }
}
