<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Authentication\Application\Security\Provider;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\Exception\InvalidEmailException;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\Email;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 */
class DomainUserProvider implements UserProviderInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $username
     *
     * @return UserInterface
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

        $userId = UserId::fromEmail($email);
        $user = $this->userRepository->load($userId);
        if (!$user instanceof User) {
            throw new UsernameNotFoundException(sprintf(
                'Username "%s" not found',
                $username
            ));
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
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
