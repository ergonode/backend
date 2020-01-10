<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class WorkflowDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\WorkflowId")
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
     * @return AbstractId|WorkflowId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
