<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Provider;

use Ergonode\Account\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 */
class SimpleTokenStorageProvider implements TokenStorageProviderInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritDoc}
     *
     * @throws AuthenticationException
     */
    public function getUser(): User
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            throw new AuthenticationException('Token not set');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException('User not set');
        }

        return $user;
    }
}
