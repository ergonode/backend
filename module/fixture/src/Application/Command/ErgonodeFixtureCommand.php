<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Application\Command;

use Doctrine\Migrations\Tools\BytesFormatter;
use Ergonode\Fixture\Infrastructure\Process\FixtureProcess;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 */
class ErgonodeFixtureCommand extends Command
{
    /**
     * @var FixtureProcess
     */
    private FixtureProcess $process;

    /**
     * @param FixtureProcess $process
     */
    public function __construct(FixtureProcess $process)
    {
        $this->process = $process;

        parent::__construct();
    }

    /**
     */
    public function configure(): void
    {
        $this->setName('ergonode:fixture:load');
        $this->setDescription('Fill database with data');
        $this->addOption('group', 'g', InputOption::VALUE_REQUIRED, 'Group');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     *
     * @throws \Ergonode\Fixture\Exception\FixtureException
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('ergonode-fixture-load');
        $this->process->process($input->getOption('group'));
        $event = $stopwatch->stop('ergonode-fixture-load');

        $this->endFixtureLoad($event, $output);
    }

    /**
     * @param StopwatchEvent  $stopwatchEvent
     * @param OutputInterface $output
     */
    private function endFixtureLoad(StopwatchEvent $stopwatchEvent, OutputInterface $output)
    {
        $output->write("\n  <comment>------------------------</comment>\n");

        $output->write(sprintf(
            "  <info>++</info> finished in %sms\n",
            $stopwatchEvent->getDuration()
        ));

        $output->write(sprintf(
            "  <info>++</info> used %s memory\n",
            BytesFormatter::formatBytes($stopwatchEvent->getMemory())
        ));
    }
}
