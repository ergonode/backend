<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Group;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;

class AttributeGroupNameChangedEvent implements AggregateEventInterface
{
    private AttributeGroupId $id;

    private TranslatableString $to;

    public function __construct(AttributeGroupId $id, TranslatableString $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): AttributeGroupId
    {
        return $this->id;
    }

    public function getTo(): TranslatableString
    {
        return $this->to;
    }
}
