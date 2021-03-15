<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Security\Provider;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Query\UserQueryInterface;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\SharedKernel\Domain\Exception\InvalidEmailException;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class EmailUserProvider implements UserProviderInterface
{
    private UserQueryInterface $query;
    private UserRepositoryInterface $repository;

    public function __construct(UserQueryInterface $query, UserRepositoryInterface $repository)
    {
        $this->query = $query;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username): User
    {
        if (empty($username)) {
            throw new UsernameNotFoundException('Empty username');
        }
        if (!is_string($username)) {
            throw new UsernameNotFoundException('Username has to be a string');
        }

        try {
            $email = new Email($username);
        } catch (InvalidEmailException $exception) {
            throw new UsernameNotFoundException('Invalid email format');
        }

        $userId = $this->query->findIdByEmail($email);
        if (!$userId || !$user = $this->repository->load($userId)) {
            throw new UsernameNotFoundException('Invalid credentials');
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): User
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
