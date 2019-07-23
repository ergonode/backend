<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\CreateUserCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;

/**
 */
class CreateUserCommandHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateUserCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateUserCommand $command)
    {
        $user = new User(
            $command->getId(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getEmail(),
            $command->getLanguage(),
            $command->getPassword(),
            $command->getRoleId(),
            $command->getAvatarId()
        );

        $this->repository->save($user);
    }
}
