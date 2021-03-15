<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Handler\Schedule;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Channel\Domain\Query\SchedulerQueryInterface;
use Ergonode\Channel\Domain\Command\Schedule\ScheduleCommand;
use Ergonode\Channel\Domain\Command\ExportChannelCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class ChannelExportScheduleCommandHandler
{
    private SchedulerQueryInterface $query;

    private CommandBusInterface $commandBus;

    public function __construct(SchedulerQueryInterface $query, CommandBusInterface $commandBus)
    {
        $this->query = $query;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ScheduleCommand $command): void
    {
        $date = $command->getDate();
        $channels = $this->query->getReadyToRun($date);

        foreach ($channels as $channel) {
            $exportId = ExportId::generate();
            $newCommand = new ExportChannelCommand($exportId, $channel);
            $this->commandBus->dispatch($newCommand, true);
            $this->query->markAsRun($channel, $date);
        }
    }
}
