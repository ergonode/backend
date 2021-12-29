<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command\Option;

use Ergonode\Attribute\Domain\Command\AttributeCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;

class MoveOptionCommand implements AttributeCommandInterface
{
    private AggregateId $id;

    private AttributeId $attributeId;

    private bool $after;

    private ?AggregateId $positionId;

    public function __construct(AggregateId $id, AttributeId $attributeId, bool $after, ?AggregateId $positionId)
    {
        $this->id = $id;
        $this->attributeId = $attributeId;
        $this->after = $after;
        $this->positionId = $positionId;
    }


    public function getId(): AggregateId
    {
        return $this->id;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }

    public function isAfter(): bool
    {
        return $this->after;
    }

    public function getPositionId(): ?AggregateId
    {
        return $this->positionId;
    }
}
