<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Webmozart\Assert\Assert;
use Ergonode\Channel\Domain\Command\UpdateSchedulerCommand;
use Ergonode\Channel\Domain\Repository\SchedulerRepositoryInterface;
use Ergonode\Channel\Domain\Entity\Scheduler;

class UpdateScheduleCommandHandler
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
     * @param UpdateSchedulerCommand $command
     */
    public function __invoke(UpdateSchedulerCommand $command)
    {
        $scheduler = $this->repository->load($command->getId());

        Assert::isInstanceOf($scheduler, Scheduler::class);
        if ($command->isActive()) {
            $scheduler->active($command->getStart(), $command->getHour(), $command->getMinute());
        } else {
            $scheduler->deActive();
        }

        $this->repository->save($scheduler);
    }
}
