<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Transformer\Domain\ValueObject\ProcessorStatus;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProcessorStatusChangedEvent implements DomainEventInterface
{
    /**
     * @var ProcessorStatus
     *
     * @JMS\Type("Ergonode\Transformer\Domain\ValueObject\ProcessorStatus")
     */
    private $from;

    /**
     * @var ProcessorStatus
     *
     * @JMS\Type("Ergonode\Transformer\Domain\ValueObject\ProcessorStatus")
     */
    private $to;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $reason;

    /**
     * @param ProcessorStatus $from
     * @param ProcessorStatus $to
     * @param null|string     $reason
     */
    public function __construct(ProcessorStatus $from, ProcessorStatus $to, ?string $reason = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->reason = $reason;
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
