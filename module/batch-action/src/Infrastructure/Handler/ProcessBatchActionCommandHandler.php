<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Command\ProcessBatchActionCommand;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Query\BatchActionQueryInterface;
use Ergonode\BatchAction\Domain\Event\BatchActionEndedEvent;
use Ergonode\Core\Application\Messenger\DomainEventBus;
use Ergonode\BatchAction\Domain\Event\BatchActionWaitingForDecisionEvent;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionStatus;

class ProcessBatchActionCommandHandler
{
    private BatchActionRepositoryInterface $repository;

    private BatchActionQueryInterface $query;

    private DomainEventBus $bus;

    public function __construct(
        BatchActionRepositoryInterface $repository,
        BatchActionQueryInterface $query,
        DomainEventBus $bus
    ) {
        $this->repository = $repository;
        $this->query = $query;
        $this->bus = $bus;
    }

    public function __invoke(ProcessBatchActionCommand $command): void
    {
        $batchAction = $this->repository->load($command->getId());

        Assert::isInstanceOf($batchAction, BatchAction::class);

        if (!$this->query->hasEntriesToProcess($batchAction->getId())) {
            $event = new BatchActionEndedEvent($batchAction->getId());
            $status = new BatchActionStatus(BatchActionStatus::ENDED);
            if (!$batchAction->isAutoEndOnErrors() && $this->query->hasErrors($batchAction->getId())) {
                $status = new BatchActionStatus(BatchActionStatus::WAITING_FOR_DECISION);
                $event = new BatchActionWaitingForDecisionEvent($batchAction->getId());
            }

            $batchAction->setStatus($status);
            $this->repository->save($batchAction);

            $this->bus->dispatch($event);
        }
    }
}
