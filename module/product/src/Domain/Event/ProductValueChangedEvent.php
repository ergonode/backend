<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class ProductValueChangedEvent implements AggregateEventInterface
{
    private ProductId $id;

    private AttributeCode $code;

    private ValueInterface $from;

    private ValueInterface $to;

    public function __construct(ProductId $id, AttributeCode $code, ValueInterface $from, ValueInterface $to)
    {
        $this->id = $id;
        $this->code = $code;
        $this->from = $from;
        $this->to = $to;
    }

    public function getAggregateId(): ProductId
    {
        return $this->id;
    }

    public function getAttributeCode(): AttributeCode
    {
        return $this->code;
    }

    public function getFrom(): ValueInterface
    {
        return $this->from;
    }

    public function getTo(): ValueInterface
    {
        return $this->to;
    }
}
