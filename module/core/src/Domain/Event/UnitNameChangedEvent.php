<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use JMS\Serializer\Annotation as JMS;

class UnitNameChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UnitId")
     */
    private UnitId $id;

    /**
     * @JMS\Type("string")
     */
    private string $from;

    /**
     * @JMS\Type("string")
     */
    private string $to;

    /**
     * UnitNameChangedEvent constructor.
     */
    public function __construct(UnitId $id, string $from, string $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    public function getAggregateId(): UnitId
    {
        return $this->id;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }
}
