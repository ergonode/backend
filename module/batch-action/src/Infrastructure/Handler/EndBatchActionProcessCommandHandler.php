<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Command\EndBatchActionProcessCommand;
use Ergonode\BatchAction\Domain\Event\BatchActionFinishedEvent;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Bus\EventBusInterface;

class EndBatchActionProcessCommandHandler
{
    private BatchActionRepositoryInterface $repository;

    private EventBusInterface $eventBus;

    private CommandBusInterface $commandBus;

    public function __construct(
        BatchActionRepositoryInterface $repository,
        EventBusInterface $eventBus,
        CommandBusInterface $commandBus
    ) {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
        $this->commandBus = $commandBus;
    }

    public function __invoke(EndBatchActionProcessCommand $command): void
    {
        if ($this->repository->isProcessEnded($command->getId())) {
            $event = new BatchActionFinishedEvent($command->getId(), $command->getType());
            $this->eventBus->dispatch($event);
        } else {
            $this->commandBus->dispatch($command, true);
        }
    }
}
