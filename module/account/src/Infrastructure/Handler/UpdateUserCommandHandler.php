<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Handler;

use Ergonode\Account\Domain\Command\UpdateUserCommand;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
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
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
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
        if ($command->getPassword()) {
            $user->changePassword($command->getPassword());
        }
        $this->repository->save($user);
    }
}
