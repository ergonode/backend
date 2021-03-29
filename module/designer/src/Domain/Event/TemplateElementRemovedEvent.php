<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class TemplateElementRemovedEvent implements AggregateEventInterface
{
    private TemplateId $id;

    private Position $position;

    public function __construct(TemplateId $id, Position $position)
    {
        $this->id = $id;
        $this->position = $position;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }
}
