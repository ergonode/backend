<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\ChangeUserAvatarCommand;
use Ergonode\Account\Domain\Command\ChangeUserPasswordCommand;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
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
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
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
        $user->changePassword($command->getPassword());
        $this->repository->save($user);
    }
}
