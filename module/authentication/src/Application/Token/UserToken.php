<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Token;

use Ergonode\Account\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class UserToken extends AbstractToken
{

    public function __construct(User $user, array $roles)
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
