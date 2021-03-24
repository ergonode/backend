<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use JMS\Serializer\Annotation as JMS;

class StatusColorChangedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $id;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
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
