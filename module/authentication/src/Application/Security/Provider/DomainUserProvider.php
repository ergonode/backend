<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Authentication\Application\Security\Provider;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Exception\InvalidEmailException;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Ergonode\Account\Domain\Query\UserQueryInterface;

/**
 */
class DomainUserProvider implements UserProviderInterface
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
     * @param string $username
     *
     * @return UserInterface
     *
     * @throws \Exception
     */
    public function loadUserByUsername($username): UserInterface
    {
        if (empty($username)) {
            throw new UsernameNotFoundException('Empty username');
        }

        try {
            $email = new Email($username);
        } catch (InvalidEmailException $exception) {
            throw new UsernameNotFoundException('Invalid email format');
        }

        $userId = $this->query->findIdByEmail($email);
        if ($userId) {
            $user = $this->repository->load($userId);
            if ($user instanceof User) {
                return $user;
            }
        }

        throw new UsernameNotFoundException(sprintf(
            'Username "%s" not found',
            $username
        ));
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws \Exception
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
