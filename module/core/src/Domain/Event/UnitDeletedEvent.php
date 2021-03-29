<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

class UnitDeletedEvent extends AbstractDeleteEvent
{
    private UnitId $id;

    public function __construct(UnitId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): UnitId
    {
        return $this->id;
    }
}
