<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Webmozart\Assert\Assert;
use Ergonode\Channel\Domain\Command\UpdateSchedulerCommand;
use Ergonode\Channel\Domain\Repository\SchedulerRepositoryInterface;
use Ergonode\Channel\Domain\Entity\Scheduler;

class UpdateScheduleCommandHandler
{
    private SchedulerRepositoryInterface $repository;

    public function __construct(SchedulerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateSchedulerCommand $command): void
    {
        $scheduler = $this->repository->load($command->getId());

        Assert::isInstanceOf($scheduler, Scheduler::class);
        $scheduler->setUp(
            $command->isActive(),
            $command->getStart(),
            $command->getHour(),
            $command->getMinute(),
        );

        $this->repository->save($scheduler);
    }
}
