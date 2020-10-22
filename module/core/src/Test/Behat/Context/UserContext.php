<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Query\UserQueryInterface;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Authentication\Application\Security\User\User as SecurityUser;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Exception;
use InvalidArgumentException;

class UserContext implements Context
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    /**
     * @var UserQueryInterface
     */
    private UserQueryInterface $query;

    /**
     * @param UserRepositoryInterface $repository
     * @param UserQueryInterface      $query
     */
    public function __construct(UserRepositoryInterface $repository, UserQueryInterface $query)
    {
        $this->repository = $repository;
        $this->query = $query;
    }

    /**
     * @param string $userEmail
     *
     * @return SecurityUser
     *
     * @throws Exception
     *
     * @Transform :user
     */
    public function castUserEmailToUser(string $userEmail): SecurityUser
    {
        $userId = $this->query->findIdByEmail(new Email($userEmail));
        if (!$userId instanceof UserId) {
            throw new InvalidArgumentException(sprintf('There is no user with email %s', $userEmail));
        }
        $user = $this->repository->load($userId);
        if (!$user instanceof User) {
            throw new InvalidArgumentException(sprintf('There is no user with email %s', $userEmail));
        }

        return new SecurityUser(
            $user->getId()->getValue(),
            $user->getPassword(),
            $user->getRoles(),
            $user->isActive(),
        );
    }
}
