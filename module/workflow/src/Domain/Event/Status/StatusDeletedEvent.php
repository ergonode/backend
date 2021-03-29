<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\SharedKernel\Domain\AggregateId;

class StatusDeletedEvent extends AbstractDeleteEvent
{
    private StatusId $id;

    public function __construct(StatusId $id)
    {
        $this->id = $id;
    }

    /**
     * @return StatusId|AggregateId
     */
    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }
}
