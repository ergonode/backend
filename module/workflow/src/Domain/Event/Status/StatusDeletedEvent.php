<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\Workflow\Domain\Entity\StatusId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class StatusDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\StatusId")
     */
    private $id;

    /**
     * @param StatusId $id
     */
    public function __construct(StatusId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AbstractId|StatusId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
