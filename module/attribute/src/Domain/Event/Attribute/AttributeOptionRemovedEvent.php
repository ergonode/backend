<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;

class AttributeOptionRemovedEvent implements AggregateEventInterface
{
    private AttributeId $id;

    private AggregateId $optionId;

    public function __construct(AttributeId $id, AggregateId $optionId)
    {
        $this->id = $id;
        $this->optionId = $optionId;
    }

    public function getAggregateId(): AttributeId
    {
        return $this->id;
    }

    public function getOptionId(): AggregateId
    {
        return $this->optionId;
    }
}
