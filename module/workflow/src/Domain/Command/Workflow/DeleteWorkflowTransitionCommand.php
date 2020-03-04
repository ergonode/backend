<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteWorkflowTransitionCommand implements DomainCommandInterface
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
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private StatusCode $source;

    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private StatusCode $destination;

    /**
     * @param WorkflowId $workflowId
     * @param StatusCode $source
     * @param StatusCode $destination
     */
    public function __construct(
        WorkflowId $workflowId,
        StatusCode $source,
        StatusCode $destination
    ) {
        $this->workflowId = $workflowId;
        $this->source = $source;
        $this->destination = $destination;
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
    public function getSource(): StatusCode
    {
        return $this->source;
    }

    /**
     * @return StatusCode
     */
    public function getDestination(): StatusCode
    {
        return $this->destination;
    }
}
