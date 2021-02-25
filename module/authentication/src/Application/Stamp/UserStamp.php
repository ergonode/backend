<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Stamp;

use Ergonode\Core\Domain\User\AggregateUserInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class UserStamp implements StampInterface
{
    private AggregateUserInterface $user;

    public function __construct(AggregateUserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser(): AggregateUserInterface
    {
        return $this->user;
    }
}
