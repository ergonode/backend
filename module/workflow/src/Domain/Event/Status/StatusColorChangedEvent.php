<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use JMS\Serializer\Annotation as JMS;

class StatusColorChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $id;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private Color $from;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private Color $to;

    public function __construct(StatusId $id, Color $from, Color $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    public function getAggregateId(): StatusId
    {
        return $this->id;
    }

    public function getFrom(): Color
    {
        return $this->from;
    }

    public function getTo(): Color
    {
        return $this->to;
    }
}
