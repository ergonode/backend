<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Security\Provider;

use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Authentication\Application\Security\User\User;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class IdUserProvider implements UserProviderInterface
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
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
            $userId = new UserId($username);
        } catch (\InvalidArgumentException $exception) {
            throw new UsernameNotFoundException('Invalid uuid format');
        }

        if (!$userId || !$user = $this->repository->load($userId)) {
            throw new UsernameNotFoundException("Username '$username' not found");
        }

        return new User(
            $user->getId()->getValue(),
            $user->getPassword(),
            $user->getRoles(),
            $user->isActive(),
        );
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
