<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Repository;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Channel\Domain\Entity\Scheduler;

/**
 */
interface SchedulerRepositoryInterface
{
    /**
     * @param AggregateId $id
     *
     * @return Scheduler
     */
    public function load(AggregateId $id): ?Scheduler;

    /**
     * @param AggregateId $id
     *
     * @return bool
     */
    public function exists(AggregateId $id) : bool;

    /**
     * @param Scheduler $aggregateRoot
     */
    public function save(Scheduler $aggregateRoot): void;

    /**
     * @param Scheduler $aggregateRoot
     */
    public function delete(Scheduler $aggregateRoot): void;
}
