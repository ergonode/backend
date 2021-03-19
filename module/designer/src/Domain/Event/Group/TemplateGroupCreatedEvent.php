<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event\Group;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;

class TemplateGroupCreatedEvent implements AggregateEventInterface
{
    private TemplateGroupId $id;
    private string $name;

    public function __construct(TemplateGroupId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getAggregateId(): TemplateGroupId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
