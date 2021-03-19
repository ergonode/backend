<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;

class ConditionSetDeletedEvent extends AbstractDeleteEvent
{
    private ConditionSetId $id;

    public function __construct(ConditionSetId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): ConditionSetId
    {
        return $this->id;
    }
}
