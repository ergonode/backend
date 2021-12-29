<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Group;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;

class AttributeGroupDeletedEvent extends AbstractDeleteEvent
{
    private AttributeGroupId $id;

    public function __construct(AttributeGroupId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): AttributeGroupId
    {
        return $this->id;
    }
}
