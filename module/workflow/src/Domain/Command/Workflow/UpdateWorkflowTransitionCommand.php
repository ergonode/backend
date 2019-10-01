<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UpdateWorkflowTransitionCommand
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\WorkflowId")
     */
    private $workflowId;

    /**
     * @var Transition
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\Transition")
     */
    private $transition;

    /**
     * @param WorkflowId $workflowId
     * @param Transition $transition
     */
    public function __construct(
        WorkflowId $workflowId,
        Transition $transition
    ) {
        $this->workflowId = $workflowId;
        $this->transition = $transition;
    }

    /**
     * @return WorkflowId
     */
    public function getWorkflowId(): WorkflowId
    {
        return $this->workflowId;
    }

    /**
     * @return Transition
     */
    public function getTransition(): Transition
    {
        return $this->transition;
    }
}
