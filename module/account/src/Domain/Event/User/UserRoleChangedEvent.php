<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

class UserRoleChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private RoleId $from;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private RoleId $to;

    public function __construct(UserId $id, RoleId $from, RoleId $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    public function getAggregateId(): UserId
    {
        return $this->id;
    }

    public function getFrom(): RoleId
    {
        return $this->from;
    }

    public function getTo(): RoleId
    {
        return $this->to;
    }
}
