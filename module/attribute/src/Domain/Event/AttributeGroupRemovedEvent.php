<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;

class AttributeGroupRemovedEvent implements AggregateEventInterface
{
    private AttributeId $id;

    private AttributeGroupId $groupId;

    public function __construct(AttributeId $id, AttributeGroupId $groupId)
    {
        $this->id = $id;
        $this->groupId = $groupId;
    }

    public function getAggregateId(): AttributeId
    {
        return $this->id;
    }

    public function getGroupId(): AttributeGroupId
    {
        return $this->groupId;
    }
}
