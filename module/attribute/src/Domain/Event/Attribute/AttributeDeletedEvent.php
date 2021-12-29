<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;

class AttributeDeletedEvent extends AbstractDeleteEvent
{
    private AttributeId $id;

    public function __construct(AttributeId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): AttributeId
    {
        return $this->id;
    }
}
