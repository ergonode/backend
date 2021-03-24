<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Repository;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Channel\Domain\Entity\Scheduler;

interface SchedulerRepositoryInterface
{
    public function load(AggregateId $id): ?Scheduler;

    public function exists(AggregateId $id): bool;

    public function save(Scheduler $aggregateRoot): void;

    public function delete(Scheduler $aggregateRoot): void;
}
