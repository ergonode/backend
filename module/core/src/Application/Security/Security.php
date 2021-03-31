<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Security;

use Ergonode\Core\Domain\User\UserInterface;
use Symfony\Component\Security\Core\Security as SymfonySecurity;

class Security
{
    private SymfonySecurity $security;

    public function __construct(SymfonySecurity $security)
    {
        $this->security = $security;
    }

    public function getUser(): ?UserInterface
    {
        $user = $this->security->getUser();

        return $user instanceof UserInterface ?
            $user :
            null;
    }
}
