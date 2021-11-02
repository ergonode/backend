<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Option;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;

class OptionMovedEvent implements AggregateEventInterface
{
    private AttributeId $id;

    private AggregateId $optionId;

    private bool $after;

    public function __construct(AttributeId $id, AggregateId $optionId, bool $after = true)
    {
        $this->id = $id;
        $this->optionId = $optionId;
        $this->after = $after;
    }

    public function getAggregateId(): AttributeId
    {
        return $this->id;
    }

    public function getOptionId(): AggregateId
    {
        return $this->optionId;
    }

    public function isAfter(): bool
    {
        return $this->after;
    }
}
