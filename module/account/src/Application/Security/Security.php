<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Security;

use Ergonode\Account\Domain\Entity\User;
use Symfony\Component\Security\Core\Security as SymfonySecurity;

class Security
{
    private SymfonySecurity $security;

    public function __construct(SymfonySecurity $security)
    {
        $this->security = $security;
    }

    public function getUser(): ?User
    {
        $user = $this->security->getUser();

        return $user instanceof User ?
            $user :
            null;
    }
}
