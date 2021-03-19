<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class TemplateRemovedEvent extends AbstractDeleteEvent
{
    private TemplateId $id;

    private ?string $reason;

    public function __construct(TemplateId $id, ?string $reason = null)
    {
        $this->id = $id;
        $this->reason = $reason;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }
}
