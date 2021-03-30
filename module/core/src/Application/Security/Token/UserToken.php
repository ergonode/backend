<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Security\Token;

use Ergonode\Core\Domain\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class UserToken extends AbstractToken
{

    public function __construct(UserInterface $user, array $roles)
    {
        parent::__construct($roles);
        $this->setUser($user);

        $this->setAuthenticated(true);
    }

    public function getCredentials(): ?string
    {
        return null;
    }
}
