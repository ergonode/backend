<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;
use Ergonode\Authentication\Application\Security\User\CachedUser;

final class UserStamp implements StampInterface
{
    private CachedUser $user;

    public function __construct(CachedUser $user)
    {
        $this->user = $user;
    }

    public function getUser(): CachedUser
    {
        return $this->user;
    }
}
