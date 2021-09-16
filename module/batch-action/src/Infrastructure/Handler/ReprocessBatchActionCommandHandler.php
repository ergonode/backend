<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Command\ReprocessBatchActionCommand;
use Webmozart\Assert\Assert;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionStatus;

class ReprocessBatchActionCommandHandler
{
    private BatchActionRepositoryInterface $repository;

    public function __construct(BatchActionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ReprocessBatchActionCommand $command): void
    {
        $batchAction = $this->repository->load($command->getId());
        Assert::isInstanceOf($batchAction, BatchAction::class);
        $batchAction->setPayload($command->getPayload());
        $batchAction->setStatus(new BatchActionStatus(BatchActionStatus::PRECESSED));
        $batchAction->setAutoEndOnErrors($command->isAutoEndOnErrors());

        $this->repository->reprocess($batchAction);
    }
}
