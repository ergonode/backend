<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Repository;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionMessage;

interface BatchActionRepositoryInterface
{
    public function load(BatchActionId $id): ?BatchAction;

    public function save(BatchAction $batchAction): void;

    public function addEntry(BatchActionId $id, AggregateId $resourceId): void;

    /**
     * @param BatchActionMessage[] $messages
     */
    public function markEntry(BatchActionId $id, AggregateId $resourceId, array $messages): void;
}
