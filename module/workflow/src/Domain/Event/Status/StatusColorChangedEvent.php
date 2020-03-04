<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class StatusColorChangedEvent implements DomainEventInterface
{
    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $id;

    /**
     * @var Color
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private Color $from;

    /**
     * @var Color
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private Color $to;

    /**
     * @param StatusId $id
     * @param Color    $from
     * @param Color    $to
     */
    public function __construct(StatusId $id, Color $from, Color $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return StatusId
     */
    public function getAggregateId(): StatusId
    {
        return $this->id;
    }

    /**
     * @return Color
     */
    public function getFrom(): Color
    {
        return $this->from;
    }

    /**
     * @return Color
     */
    public function getTo(): Color
    {
        return $this->to;
    }
}
