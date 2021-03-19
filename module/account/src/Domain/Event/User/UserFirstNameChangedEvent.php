<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;

use Ergonode\EventSourcing\Domain\Event\AbstractStringBasedChangedEvent;

class UserFirstNameChangedEvent extends AbstractStringBasedChangedEvent
{
    private UserId $id;

    public function __construct(UserId $id, string $to)
    {
        $this->id = $id;
        parent::__construct($to);
    }

    public function getAggregateId(): UserId
    {
        return $this->id;
    }
}
