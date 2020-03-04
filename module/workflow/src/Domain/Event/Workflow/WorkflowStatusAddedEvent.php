<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class WorkflowStatusAddedEvent implements DomainEventInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private StatusCode $code;

    /**
     * @param WorkflowId $id
     * @param StatusCode $code
     */
    public function __construct(WorkflowId $id, StatusCode $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    /**
     * @return WorkflowId
     */
    public function getAggregateId(): WorkflowId
    {
        return $this->id;
    }


    /**
     * @return StatusCode
     */
    public function getCode(): StatusCode
    {
        return $this->code;
    }
}
