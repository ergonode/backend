<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class StatusColorChangedEvent implements AggregateEventInterface
{
    private StatusId $id;

    private Color $to;

    public function __construct(StatusId $id, Color $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): StatusId
    {
        return $this->id;
    }

    public function getTo(): Color
    {
        return $this->to;
    }
}
