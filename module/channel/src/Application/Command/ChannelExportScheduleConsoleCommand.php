<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Command;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ergonode\Channel\Domain\Command\Schedule\ScheduleCommand;

/**
 */
class ChannelExportScheduleConsoleCommand extends Command
{
    private const NAME = 'channel:export:schedule';

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        parent::__construct(static::NAME);

        $this->commandBus = $commandBus;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $time = new \DateTime();

        $output->writeln(sprintf('Run schedule for %s', $time->format('Y-m-d H:i:sO')));

        $command = new ScheduleCommand($time);
        $this->commandBus->dispatch($command, true);

        return 0;
    }
}
