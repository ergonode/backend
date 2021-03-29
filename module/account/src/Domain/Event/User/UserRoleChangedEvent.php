<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;

class UserRoleChangedEvent implements AggregateEventInterface
{
    private UserId $id;

    private RoleId $to;

    public function __construct(UserId $id, RoleId $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): UserId
    {
        return $this->id;
    }

    public function getTo(): RoleId
    {
        return $this->to;
    }
}
