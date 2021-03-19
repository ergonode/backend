<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Event\Relation;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;

class ChildRemovedFromProductEvent implements AggregateEventInterface
{
    private ProductId $id;

    private ProductId $childId;

    public function __construct(ProductId $id, ProductId $childId)
    {
        $this->id = $id;
        $this->childId = $childId;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getChildId(): ProductId
    {
        return $this->childId;
    }
}
