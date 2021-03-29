<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class TemplateNameChangedEvent implements AggregateEventInterface
{
    private TemplateId $id;

    private string $to;

    public function __construct(TemplateId $id, string $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getTo(): string
    {
        return $this->to;
    }
}
