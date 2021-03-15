<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\User\UpdateUserCommand;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Account\Infrastructure\Encoder\UserPasswordEncoderInterface;
use Webmozart\Assert\Assert;

class UpdateUserCommandHandler
{
    private UserRepositoryInterface $repository;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(
        UserRepositoryInterface $repository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->repository = $repository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->repository->load($command->getId());
        Assert::notNull($user);

        $user->changeFirstName($command->getFirstName());
        $user->changeLastName($command->getLastName());
        $user->changeLanguage($command->getLanguage());
        $user->changeRole($command->getRoleId());
        $user->changeLanguagePrivilegesCollection($command->getLanguagePrivilegesCollection());

        if ($user->isActive() !== $command->isActive()) {
            $command->isActive() ? $user->activate() : $user->deactivate();
        }

        if ($command->getPassword() instanceof Password) {
            $encodedPassword = $this->userPasswordEncoder->encode($user, $command->getPassword());
            if ($user->getPassword() !== $encodedPassword->getValue()) {
                $user->changePassword($encodedPassword);
            }
        }

        $this->repository->save($user);
    }
}
