<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Application\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Mailer\Domain\Command\SendMailCommand;
use Ergonode\Mailer\Domain\TestMail;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestMessageCommand extends Command
{
    private const NAME = 'ergonode:mailer:test';

    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        parent::__construct(static::NAME);

        $this->commandBus = $commandBus;
    }

    /**
     * Command configuration
     */
    public function configure(): void
    {
        $this->setDescription('Send test email message');
        $this->addArgument('to', InputArgument::REQUIRED, 'Email');
        $this->addOption('language', 'l', InputOption::VALUE_OPTIONAL, 'Language', 'en_US');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $to = new Email($input->getArgument('to'));
        $language = new Language($input->getOption('language'));
        $mail = new TestMail($to, $language);
        $command = new SendMailCommand($mail);
        $this->commandBus->dispatch($command);

        return 0;
    }
}
