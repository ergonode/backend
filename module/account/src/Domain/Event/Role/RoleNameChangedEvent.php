<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\EventSourcing\Domain\Event\AbstractStringBasedChangedEvent;

class RoleNameChangedEvent extends AbstractStringBasedChangedEvent
{
    private RoleId $id;

    public function __construct(RoleId $id, string $to)
    {
        $this->id = $id;
        parent::__construct($to);
    }

    public function getAggregateId(): RoleId
    {
        return $this->id;
    }
}
