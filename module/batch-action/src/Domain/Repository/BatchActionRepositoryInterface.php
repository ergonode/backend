<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Repository;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Core\Infrastructure\Model\Relationship;

interface BatchActionRepositoryInterface
{
    public function load(BatchActionId $id): ?BatchAction;

    public function save(BatchAction $batchAction): void;

    public function addEntry(BatchActionId $id, AggregateId $resourceId): void;

    public function markEntryAsSuccess(BatchActionId $id, AggregateId $resourceId): void;

    public function markEntryAsUnsuccess(BatchActionId $id, AggregateId $resourceId, Relationship $relationship): void;
}
