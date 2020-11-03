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

class CreateBatchActionCommandHandler
{
    private BatchActionRepositoryInterface $repository;

    public function __construct(BatchActionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateBatchActionCommand $command): void
    {
        $batchAction = new BatchAction($command->getId(), $command->getType(), $command->getAction());
        $this->repository->save($batchAction);

        foreach ($command->getIds() as $id) {
            $this->repository->addEntry($batchAction->getId(), $id);
        }
    }
}
