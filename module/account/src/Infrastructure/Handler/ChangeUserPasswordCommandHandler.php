<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\User\ChangeUserPasswordCommand;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\Password;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Webmozart\Assert\Assert;

/**
 */
class ChangeUserPasswordCommandHandler
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
     * @param ChangeUserPasswordCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ChangeUserPasswordCommand $command)
    {
        $user = $this->repository->load($command->getId());
        Assert::notNull($user);

        $encodedPassword = $this->userPasswordEncoder->encodePassword($user, $command->getPassword()->getValue());
        $password = new Password($encodedPassword);
        $user->changePassword($password);

        $this->repository->save($user);
    }
}
