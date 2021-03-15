<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Command;

use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    private const NAME = 'ergonode:user:create';

    private CommandBusInterface $commandBus;

    private RoleQueryInterface $query;

    public function __construct(CommandBusInterface $commandBus, RoleQueryInterface $query)
    {
        parent::__construct(static::NAME);

        $this->query = $query;
        $this->commandBus = $commandBus;
    }

    /**
     * Command configuration
     */
    public function configure(): void
    {
        $this->setDescription('Creates a new valid user');
        $this->addArgument('email', InputArgument::REQUIRED, 'user email.');
        $this->addArgument('first_name', InputArgument::REQUIRED, 'First name');
        $this->addArgument('last_name', InputArgument::REQUIRED, 'Last name');
        $this->addArgument('password', InputArgument::REQUIRED, 'Password');
        $this->addArgument('language', InputArgument::REQUIRED, 'Language');
        $this->addArgument('role', InputArgument::OPTIONAL, 'Role', 'Admin');
    }

    /**
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $firstName = $input->getArgument('first_name');
        $lastName = $input->getArgument('last_name');
        $role = $input->getArgument('role');
        $email = new Email($input->getArgument('email'));
        $password = new Password($input->getArgument('password'));
        $language = new Language($input->getArgument('language'));

        $roleId = array_search($role, $this->query->getDictionary(), true);

        if (false === $roleId) {
            $output->writeln(sprintf('<error>Can\'t find role %s</error>', $role));

            return 1;
        }

        $command = new \Ergonode\Account\Domain\Command\User\CreateUserCommand(
            $firstName,
            $lastName,
            $email,
            $language,
            $password,
            new RoleId($roleId)
        );
        $this->commandBus->dispatch($command);

        $output->writeln('<info>User created.</info>');

        return 0;
    }
}
