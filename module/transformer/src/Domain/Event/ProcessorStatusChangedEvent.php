<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Transformer\Domain\Entity\ProcessorId;
use Ergonode\Transformer\Domain\ValueObject\ProcessorStatus;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProcessorStatusChangedEvent implements DomainEventInterface
{
    /**
     * @var ProcessorId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\ProcessorId")
     */
    private ProcessorId $id;

    /**
     * @var ProcessorStatus
     *
     * @JMS\Type("Ergonode\Transformer\Domain\ValueObject\ProcessorStatus")
     */
    private ProcessorStatus $from;

    /**
     * @var ProcessorStatus
     *
     * @JMS\Type("Ergonode\Transformer\Domain\ValueObject\ProcessorStatus")
     */
    private ProcessorStatus $to;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private ?string $reason;

    /**
     * @param ProcessorId     $id
     * @param ProcessorStatus $from
     * @param ProcessorStatus $to
     * @param null|string     $reason
     */
    public function __construct(ProcessorId $id, ProcessorStatus $from, ProcessorStatus $to, ?string $reason = null)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->reason = $reason;
    }

    /**
     * @return AbstractId|ProcessorId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return ProcessorStatus
     */
    public function getFrom(): ProcessorStatus
    {
        return $this->from;
    }

    /**
     * @return ProcessorStatus
     */
    public function getTo(): ProcessorStatus
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
