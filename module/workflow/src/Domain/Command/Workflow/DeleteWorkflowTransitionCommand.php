<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Command\WorkflowCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class DeleteWorkflowTransitionCommand implements WorkflowCommandInterface
{
    private WorkflowId $workflowId;

    private StatusId $from;

    private StatusId $to;

    public function __construct(
        WorkflowId $workflowId,
        StatusId $from,
        StatusId $to
    ) {
        $this->workflowId = $workflowId;
        $this->from = $from;
        $this->to = $to;
    }

    public function getWorkflowId(): WorkflowId
    {
        return $this->workflowId;
    }

    public function getFrom(): StatusId
    {
        return $this->from;
    }

    public function getTo(): StatusId
    {
        return $this->to;
    }

    /**
     * @deprecated use getFrom instead
     */
    public function getSource(): StatusId
    {
        @trigger_error(
            sprintf(
                '%1$s::getSource is deprecated and will be removed in 2.0, use %1$s::getFrom instead',
                self::class,
            ),
            \E_USER_DEPRECATED
        );

        return $this->getFrom();
    }

    /**
     * @deprecated use getTo instead
     */
    public function getDestination(): StatusId
    {
        @trigger_error(
            sprintf(
                '%1$s::getDestination is deprecated and will be removed in 2.0, use %1$s::getTo instead',
                self::class,
            ),
            \E_USER_DEPRECATED
        );

        return $this->getTo();
    }
}
