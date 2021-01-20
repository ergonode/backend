<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Stamp;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class UserStamp implements StampInterface
{
    private UserId $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
