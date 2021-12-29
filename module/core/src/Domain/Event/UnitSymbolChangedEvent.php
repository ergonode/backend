<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

class UnitSymbolChangedEvent implements AggregateEventInterface
{
    private UnitId $id;

    private string $to;

    public function __construct(UnitId $id, string $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): UnitId
    {
        return $this->id;
    }

    public function getTo(): string
    {
        return $this->to;
    }
}
