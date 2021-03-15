<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Provider;

use Ergonode\Account\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SimpleAuthenticatedUserProvider implements AuthenticatedUserProviderInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritDoc}
     *
     * @throws AuthenticationException
     */
    public function provide(): User
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            throw new AuthenticationException('Authentication token not set');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException('User not set');
        }

        return $user;
    }
}
