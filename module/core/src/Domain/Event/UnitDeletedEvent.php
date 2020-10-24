<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use JMS\Serializer\Annotation as JMS;

class UnitDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UnitId")
     */
    private UnitId $id;

    public function __construct(UnitId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): UnitId
    {
        return $this->id;
    }
}
