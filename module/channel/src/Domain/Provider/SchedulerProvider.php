<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Provider;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Channel\Domain\Entity\Scheduler;
use Ergonode\Channel\Domain\Repository\SchedulerRepositoryInterface;

/**
 */
class SchedulerProvider
{
    /**
     * @var SchedulerRepositoryInterface
     */
    private SchedulerRepositoryInterface $repository;

    /**
     * @param SchedulerRepositoryInterface $repository
     */
    public function __construct(SchedulerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AggregateId $id
     *
     * @return Scheduler
     */
    public function provide(AggregateId $id): Scheduler
    {
        $scheduler = $this->repository->load($id);
        if (null === $scheduler) {
            $scheduler = new Scheduler($id);
            $this->repository->save($scheduler);
        }

        return $scheduler;
    }
}
