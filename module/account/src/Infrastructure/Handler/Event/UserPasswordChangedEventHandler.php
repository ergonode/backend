<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler\Event;

use Ergonode\Account\Domain\Event\User\UserPasswordChangedEvent;
use Ergonode\Account\Domain\Mail\UserPasswordChangedMail;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Mailer\Domain\Command\SendMailCommand;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

class UserPasswordChangedEventHandler
{
    private UserRepositoryInterface $userRepository;

    private CommandBusInterface $commandBus;

    public function __construct(UserRepositoryInterface $userRepository, CommandBusInterface $commandBus)
    {
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
    }

    public function __invoke(UserPasswordChangedEvent $event): void
    {
        $user = $this->userRepository->load($event->getAggregateId());
        if ($user) {
            $mail = new UserPasswordChangedMail($user);
            $command = new SendMailCommand($mail);
            $this->commandBus->dispatch($command);
        }
    }
}
