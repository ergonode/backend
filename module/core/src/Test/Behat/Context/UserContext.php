<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserContext implements Context
{
    private UserProviderInterface $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @throws Exception
     *
     * @Transform :user
     */
    public function castUserEmailToUser(string $userEmail): UserInterface
    {
        return $this->userProvider->loadUserByUsername($userEmail);
    }
}
