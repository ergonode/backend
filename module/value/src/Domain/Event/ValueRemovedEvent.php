<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class ValueRemovedEvent implements AggregateEventInterface
{
    private AggregateId $id;

    private AttributeCode $code;

    private ValueInterface $old;

    public function __construct(AggregateId $id, AttributeCode $code, ValueInterface $old)
    {
        $this->id = $id;
        $this->code = $code;
        $this->old = $old;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getAttributeCode(): AttributeCode
    {
        return $this->code;
    }

    public function getOld(): ValueInterface
    {
        return $this->old;
    }
}
