<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\Status;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class UpdateWorkflowCommand
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\WorkflowId")
     */
    private $id;

    /**
     * @var Status[]
     *
     * @JMS\Type("array<string, Ergonode\Workflow\Domain\ValueObject\Status>")
     */
    private $statuses;

    /**
     * @param WorkflowId $id
     * @param array      $statuses
     *
     */
    public function __construct(WorkflowId $id, array $statuses = [])
    {
        Assert::allIsInstanceOf($statuses, Status::class);

        $this->id = $id;
        $this->statuses = $statuses;
    }

    /**
     * @return WorkflowId
     */
    public function getId(): WorkflowId
    {
        return $this->id;
    }

    /**
     * @return Status[]
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }
}
