<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductValueRemovedEvent implements AggregateEventInterface
{
    private ProductId $id;

    private AttributeCode $code;

    public function __construct(ProductId $id, AttributeCode $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    public function getAggregateId(): ProductId
    {
        return $this->id;
    }

    public function getAttributeCode(): AttributeCode
    {
        return $this->code;
    }
}
