<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Event\Bind;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class BindAddedToProductEvent implements AggregateEventInterface
{
    private ProductId $id;

    private AttributeId $attributeId;

    public function __construct(ProductId $id, AttributeId $attributeId)
    {
        $this->id = $id;
        $this->attributeId = $attributeId;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }
}
