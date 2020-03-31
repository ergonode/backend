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

/**
 */
class UnitNameChangedEvent implements DomainEventInterface
{
    /**
     * @var UnitId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UnitId")
     */
    private UnitId $id;

    /**
     * @var string $from
     *
     * @JMS\Type("string")
     */
    private string $from;

    /**
     * @var string $to
     *
     * @JMS\Type("string")
     */
    private string $to;

    /**
     * UnitNameChangedEvent constructor.
     *
     * @param UnitId $id
     * @param string $from
     * @param string $to
     */
    public function __construct(UnitId $id, string $from, string $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return UnitId
     */
    public function getAggregateId(): UnitId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }
}
