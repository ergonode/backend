<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Status;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class SetDefaultStatusCommand implements DomainCommandInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $workflowId;

    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\StatusCode")
     */
    private StatusCode $statusCode;

    /**
     * @param WorkflowId $workflowId
     * @param StatusCode $statusCode
     */
    public function __construct(WorkflowId $workflowId, StatusCode $statusCode)
    {
        $this->workflowId = $workflowId;
        $this->statusCode = $statusCode;
    }

    /**
     * @return WorkflowId
     */
    public function getWorkflowId(): WorkflowId
    {
        return $this->workflowId;
    }

    /**
     * @return StatusCode
     */
    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }
}
