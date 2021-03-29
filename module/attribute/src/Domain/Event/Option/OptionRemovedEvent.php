<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Option;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;

class OptionRemovedEvent extends AbstractDeleteEvent
{
    private AggregateId $id;

    public function __construct(AggregateId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }
}
