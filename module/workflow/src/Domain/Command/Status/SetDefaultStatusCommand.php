<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Status;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class SetDefaultStatusCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $workflowId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $statusId;

    public function __construct(WorkflowId $workflowId, StatusId $statusId)
    {
        $this->workflowId = $workflowId;
        $this->statusId = $statusId;
    }

    public function getWorkflowId(): WorkflowId
    {
        return $this->workflowId;
    }

    public function getStatusId(): StatusId
    {
        return $this->statusId;
    }
}
