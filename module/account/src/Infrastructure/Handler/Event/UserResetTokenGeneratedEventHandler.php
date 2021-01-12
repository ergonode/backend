<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler\Event;

use Ergonode\Account\Domain\Event\User\UserResetTokenGeneratedEvent;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Domain\ResetTokenMail;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Mailer\Domain\Command\SendMailCommand;

class UserResetTokenGeneratedEventHandler
{
    private UserRepositoryInterface $userRepository;

    private CommandBusInterface $commandBus;

    public function __construct(UserRepositoryInterface $userRepository, CommandBusInterface $commandBus)
    {
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
    }

    public function __invoke(UserResetTokenGeneratedEvent $event): void
    {
        $user = $this->userRepository->load($event->getUserId());
        if ($user) {
            $mail = new ResetTokenMail($user->getEmail(), $user->getLanguage(), $event->getToken(), $event->getUrl());
            $command = new SendMailCommand($mail);
            $this->commandBus->dispatch($command);
        }
    }
}
