<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\UpdateUserCommand;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Account\Infrastructure\Encoder\UserPasswordEncoderInterface;
use Webmozart\Assert\Assert;

/**
 */
class UpdateUserCommandHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @param UserRepositoryInterface      $repository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(
        UserRepositoryInterface $repository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->repository = $repository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param UpdateUserCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateUserCommand $command)
    {
        $user = $this->repository->load($command->getId());
        Assert::notNull($user);

        $user->changeFirstName($command->getFirstName());
        $user->changeLastName($command->getLastName());
        $user->changeLanguage($command->getLanguage());
        $user->changeRole($command->getRoleId());

        if ($command->getPassword() instanceof Password) {
            $encodedPassword = $this->userPasswordEncoder->encode($user, $command->getPassword());
            if ($user->getPassword() !== $encodedPassword->getValue()) {
                $user->setPassword($encodedPassword);
                $user->changePassword($encodedPassword);
            }
        }

        $this->repository->save($user);
    }
}
