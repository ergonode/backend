<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Generator\Command;

use Ergonode\Generator\Generator\EntityGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class TestCommand extends Command
{
    /**
     * @var EntityGenerator
     */
    private $generator;

    /**
     * @param EntityGenerator $generator
     */
    public function __construct(EntityGenerator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }

    /**
     */
    public function configure(): void
    {
        $this->setName('ergonode:generator:test');
        $this->setDescription('Test module generation');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('entity', InputArgument::REQUIRED, 'Entity name');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->generator->generate($input->getArgument('module'), $input->getArgument('entity'));
    }
}
