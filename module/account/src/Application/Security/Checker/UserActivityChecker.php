<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Security\Checker;

use Ergonode\Core\Domain\User\UserInterface as DomainUserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserActivityChecker implements UserCheckerInterface
{
    /**
     * {@inheritDoc}
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof DomainUserInterface) {
            return;
        }

        if (!$user->isActive()) {
            throw new AuthenticationException('User not active');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof DomainUserInterface) {
            return;
        }

        if (!$user->isActive()) {
            throw new AuthenticationException('User not active');
        }
    }
}
