<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\ValueObject\Status;
use JMS\Serializer\Annotation as JMS;

/**
 */
class WorkflowStatusChangedEvent implements DomainEventInterface
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @var Status
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\Status")
     */
    private $from;

    /**
     * @var Status
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\Status")
     */
    private $to;

    /**
     * @param string $code
     * @param Status $from
     * @param Status $to
     */
    public function __construct(string $code, Status $from, Status $to)
    {
        $this->code = $code;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return Status
     */
    public function getFrom(): Status
    {
        return $this->from;
    }

    /**
     * @return Status
     */
    public function getTo(): Status
    {
        return $this->to;
    }
}
