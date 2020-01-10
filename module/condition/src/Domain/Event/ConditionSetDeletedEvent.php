<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ConditionSetDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $id;

    /**
     * @param ConditionSetId $id
     */
    public function __construct(ConditionSetId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AbstractId|ConditionSetId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
