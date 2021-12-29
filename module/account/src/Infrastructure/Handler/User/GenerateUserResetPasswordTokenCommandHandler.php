<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler\User;

use Ergonode\Account\Domain\Command\User\GenerateUserResetPasswordTokenCommand;
use Ergonode\Account\Domain\Entity\UserResetPasswordToken;
use Ergonode\Account\Domain\Event\User\UserResetTokenGeneratedEvent;
use Ergonode\Account\Domain\Repository\UserResetPasswordTokenRepositoryInterface;
use Ergonode\Account\Infrastructure\Generator\ResetTokenGeneratorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GenerateUserResetPasswordTokenCommandHandler
{
    private const INTERVAL = 'PT1H';

    private UserResetPasswordTokenRepositoryInterface $repository;

    private ResetTokenGeneratorInterface $generator;

    private MessageBusInterface $eventBus;

    public function __construct(
        UserResetPasswordTokenRepositoryInterface $repository,
        ResetTokenGeneratorInterface $generator,
        MessageBusInterface $eventBus
    ) {
        $this->repository = $repository;
        $this->generator = $generator;
        $this->eventBus = $eventBus;
    }

    public function __invoke(GenerateUserResetPasswordTokenCommand $command): void
    {
        $token = $this->generator->getToken();
        $expiresAt = new \DateTime();
        $expiresAt->add(new \DateInterval(self::INTERVAL));
        $userResetPasswordToken = new UserResetPasswordToken($command->getId(), $token, $expiresAt);

        $this->repository->save($userResetPasswordToken);

        $this->eventBus->dispatch(new UserResetTokenGeneratedEvent($command->getId(), $token, $command->getUrl()));
    }
}
