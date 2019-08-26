<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Entity\WorkflowId;

/**
 */
class DeleteWorkflowCommand
{
    /**
     * @var WorkflowId
     */
    private $id;

    /**
     * @param WorkflowId $id
     */
    public function __construct(WorkflowId $id)
    {
        $this->id = $id;
    }

    /**
     * @return WorkflowId
     */
    public function getId(): WorkflowId
    {
        return $this->id;
    }
}
