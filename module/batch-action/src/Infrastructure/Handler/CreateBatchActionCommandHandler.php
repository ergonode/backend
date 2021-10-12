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
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionFilterIdsProvider;

class CreateBatchActionCommandHandler
{
    private BatchActionRepositoryInterface $repository;

    private BatchActionFilterIdsProvider $filterProvider;

    public function __construct(
        BatchActionRepositoryInterface $repository,
        BatchActionFilterIdsProvider $filterProvider
    ) {
        $this->repository = $repository;
        $this->filterProvider = $filterProvider;
    }

    public function __invoke(CreateBatchActionCommand $command): void
    {
        $type = $command->getType();
        $id = $command->getId();
        $batchAction = new BatchAction($id, $type, $command->getPayload(), $command->isAutoEndOnErrors());
        $this->repository->save($batchAction);

        $ids = $this->filterProvider->provide($type)->filter($command->getFilter());

        foreach ($ids as $resourceId) {
            $this->repository->addEntry($id, $resourceId);
        }
    }
}
