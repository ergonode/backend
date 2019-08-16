<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\ValueObject\Status;
use JMS\Serializer\Annotation as JMS;

/**
 */
class WorkflowStatusAddedEvent implements DomainEventInterface
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
    private $status;

    /**
     * @param string $code
     * @param Status $status
     */
    public function __construct(string $code, Status $status)
    {
        $this->code = $code;
        $this->status = $status;
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
    public function getStatus(): Status
    {
        return $this->status;
    }
}
