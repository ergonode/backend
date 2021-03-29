<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class AttributeBoolParameterChangeEvent implements AggregateEventInterface
{
    private AttributeId $id;

    private string $name;

    private bool $to;

    public function __construct(AttributeId $id, string $name, bool $to)
    {
        $this->id = $id;
        $this->name = $name;
        $this->to = $to;
    }

    public function getAggregateId(): AttributeId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTo(): bool
    {
        return $this->to;
    }
}
