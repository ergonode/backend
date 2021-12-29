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
use Webmozart\Assert\Assert;
use Ergonode\BatchAction\Domain\Entity\BatchAction;

class ProcessBatchActionEntryCommandHandler
{
    private BatchActionProcessorProvider $provider;

    private BatchActionRepositoryInterface $repository;

    public function __construct(
        BatchActionProcessorProvider $provider,
        BatchActionRepositoryInterface $repository
    ) {
        $this->provider = $provider;
        $this->repository = $repository;
    }

    public function __invoke(ProcessBatchActionEntryCommand $command): array
    {
        $batchAction = $this->repository->load($command->getId());

        Assert::isInstanceOf($batchAction, BatchAction::class);

        $id = $batchAction->getId();
        $type = $batchAction->getType();
        $resourceId = $command->getResourceId();
        $payload = $batchAction->getPayload();

        $processor = $this->provider->provide($type);

        return $processor->process($id, $resourceId, $payload);
    }
}
