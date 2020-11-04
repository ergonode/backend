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
use Symfony\Component\Messenger\MessageBusInterface;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionEntryCommand;

class CreateBatchActionCommandHandler
{
    private BatchActionRepositoryInterface $repository;

    private MessageBusInterface $messageBus;

    public function __construct(BatchActionRepositoryInterface $repository, MessageBusInterface $messageBus)
    {
        $this->repository = $repository;
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreateBatchActionCommand $command): void
    {
        $batchAction = new BatchAction($command->getId(), $command->getType(), $command->getAction());
        $this->repository->save($batchAction);

        foreach ($command->getIds() as $resourceId) {
            $batchActionId = $batchAction->getId();
            $this->repository->addEntry($batchActionId, $resourceId);
            $this->messageBus->dispatch(new ProcessBatchActionEntryCommand($batchActionId, $resourceId));
        }
    }
}
