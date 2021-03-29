<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;

class RoleDeletedEvent extends AbstractDeleteEvent
{
    private RoleId $id;

    public function __construct(RoleId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): RoleId
    {
        return $this->id;
    }
}
