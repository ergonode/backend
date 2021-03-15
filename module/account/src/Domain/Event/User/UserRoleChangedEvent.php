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
use JMS\Serializer\Annotation as JMS;

class UserRoleChangedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
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
