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

class DeleteOptionCommand implements AttributeCommandInterface
{
    private AggregateId $id;

    private AttributeId $attributeId;

    public function __construct(AggregateId $id, AttributeId $attributeId)
    {
        $this->id = $id;
        $this->attributeId = $attributeId;
    }

    public function getId(): AggregateId
    {
        return $this->id;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }
}
