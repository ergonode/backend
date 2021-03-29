<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class TemplateGroupChangedEvent implements AggregateEventInterface
{
    private TemplateId $id;

    private TemplateGroupId $to;

    public function __construct(TemplateId $id, TemplateGroupId $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getNew(): TemplateGroupId
    {
        return $this->to;
    }
}
