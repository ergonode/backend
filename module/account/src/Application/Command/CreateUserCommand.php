<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Command;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

/**
 */
class CreateUserCommand extends Command
{
    private const NAME = 'ergonode:user:create';

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var RoleQueryInterface
     */
    private RoleQueryInterface $query;

    /**
     * @param CommandBusInterface $commandBus
     * @param RoleQueryInterface  $query
     */
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
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

        if ($roleId) {
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
        } else {
            $output->writeln(sprintf('<error>Can\'t find role %s</error>', $role));
        }

        return 1;
    }
}
