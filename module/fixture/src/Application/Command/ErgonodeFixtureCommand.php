<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Application\Command;

use Ergonode\Fixture\Infrastructure\Process\FixtureProcess;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class ErgonodeFixtureCommand extends Command
{
    /**
     * @var FixtureProcess
     */
    private $process;

    /**
     * @param FixtureProcess $process
     */
    public function __construct(FixtureProcess $process)
    {
        $this->process = $process;

        parent::__construct();
    }

    /**
     *
     */
    public function configure(): void
    {
        $this->setName('ergonode:fixture:load');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null|int|void
     * @throws \Ergonode\Fixture\Exception\FixtureException
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->process->process();
    }
}
