<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Repository;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\SharedKernel\Domain\AbstractId;
use Ergonode\SharedKernel\Domain\AggregateId;

interface BatchActionRepositoryInterface
{
    public function load(BatchActionId $id): ?BatchAction;

    public function save(BatchAction $batchAction): void;

    public function addResource(BatchActionId $id, AbstractId $entryId): void;

    public function markResourceAsSuccess(BatchActionId $id, AggregateId $resourceId): void;

    public function markResourceAsUnsuccess(BatchActionId $id, AggregateId $resourceId, string $message): void;
}
