<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class TemplateDefaultImageChangedEvent implements AggregateEventInterface
{
    private TemplateId $id;

    private AttributeId $to;

    public function __construct(TemplateId $id, AttributeId $to)
    {
        $this->id = $id;
        $this->to = $to;
    }
    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getTo(): AttributeId
    {
        return $this->to;
    }
}
