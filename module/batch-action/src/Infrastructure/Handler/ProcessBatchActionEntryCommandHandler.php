<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Command\ProcessBatchActionEntryCommand;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionProcessorProvider;

class ProcessBatchActionEntryCommandHandler
{
    private BatchActionProcessorProvider $provider;

    private BatchActionRepositoryInterface $repository;

    public function __construct(BatchActionProcessorProvider $provider, BatchActionRepositoryInterface $repository)
    {
        $this->provider = $provider;
        $this->repository = $repository;
    }

    public function __invoke(ProcessBatchActionEntryCommand $command): void
    {
        $id = $command->getId();
        $type = $command->getType();
        $resourceId = $command->getResourceId();
        $payload = $command->getPayload();

        $processor = $this->provider->provide($type);
        $message = $processor->process($id, $resourceId, $payload);
        $this->repository->markEntry($id, $resourceId, $message);
    }
}
