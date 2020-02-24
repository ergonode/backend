<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\User\ChangeUserAvatarCommand;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class ChangeUserAvatarCommandHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ChangeUserAvatarCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ChangeUserAvatarCommand $command)
    {
        $user = $this->repository->load($command->getId());
        Assert::notNull($user);
        $user->changeAvatar($command->getAvatarId());
        $this->repository->save($user);
    }
}
