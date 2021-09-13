<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Command\EndBatchActionCommand;
use Ergonode\BatchAction\Domain\Event\BatchActionEndedEvent;
use Ergonode\Core\Application\Messenger\DomainEventBus;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionStatus;

class EndBatchActionCommandHandler
{
    private BatchActionRepositoryInterface $repository;

    private DomainEventBus $bus;

    public function __construct(BatchActionRepositoryInterface $repository, DomainEventBus $bus)
    {
        $this->repository = $repository;
        $this->bus = $bus;
    }

    public function __invoke(EndBatchActionCommand $command): void
    {
        $batchAction = $this->repository->load($command->getId());
        Assert::isInstanceOf($batchAction, BatchAction::class);

        if ($batchAction->getStatus()->isWaitingForDecision()) {
            $batchAction->setStatus(new BatchActionStatus(BatchActionStatus::ENDED));
            $this->repository->save($batchAction);
            $event = new BatchActionEndedEvent($command->getId());
            $this->bus->dispatch($event);
        }
    }
}
