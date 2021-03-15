<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\User\ChangeUserPasswordCommand;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\Password;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Webmozart\Assert\Assert;

class ChangeUserPasswordCommandHandler
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
    public function __invoke(ChangeUserPasswordCommand $command): void
    {
        $user = $this->repository->load($command->getId());
        Assert::notNull($user);

        $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $command->getPassword()->getValue());

        $user->changePassword(new Password($encodedPassword));

        $this->repository->save($user);
    }
}
