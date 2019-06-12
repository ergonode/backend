<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Command;

use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Authentication\Entity\User;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 */
class CreateUserCommand extends Command
{
    private const NAME = 'ergonode:user:create';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param UserPasswordEncoderInterface $encoder
     * @param MessageBusInterface          $messageBus
     */
    public function __construct(UserPasswordEncoderInterface $encoder, MessageBusInterface $messageBus)
    {
        parent::__construct(static::NAME);
        $this->encoder = $encoder;
        $this->messageBus = $messageBus;
    }

    /**
     * Command configuration
     */
    public function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'user email.');
        $this->addArgument('first_name', InputArgument::REQUIRED, 'First name');
        $this->addArgument('last_name', InputArgument::REQUIRED, 'Last name');
        $this->addArgument('password', InputArgument::REQUIRED, 'Password');
        $this->addArgument('language', InputArgument::REQUIRED, 'Language');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $email = $input->getArgument('email');
        $firstName = $input->getArgument('first_name');
        $lastName = $input->getArgument('last_name');
        $password = $input->getArgument('password');
        $language = new Language($input->getArgument('language'));
        $password = new Password($this->encoder->encodePassword(new User($email, $password), $password));

        $command = new \Ergonode\Account\Domain\Command\CreateUserCommand($firstName, $lastName, $email, $language, $password);
        $this->messageBus->dispatch($command);

        $output->writeln('<info>User created.</info>');
    }
}
