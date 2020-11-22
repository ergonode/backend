<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;

class ProductValueRemovedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
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
