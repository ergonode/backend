<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use JMS\Serializer\Annotation as JMS;

class ConditionSetDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ConditionSetId $id;

    /**
     * @param ConditionSetId $id
     */
    public function __construct(ConditionSetId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ConditionSetId
     */
    public function getAggregateId(): ConditionSetId
    {
        return $this->id;
    }
}
