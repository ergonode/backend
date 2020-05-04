<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event\Bind;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
class BindAddedToProductEvent implements DomainEventInterface
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeId;

    /**
     * @param ProductId   $id
     * @param AttributeId $attributeId
     */
    public function __construct(ProductId $id, AttributeId $attributeId)
    {
        $this->id = $id;
        $this->attributeId = $attributeId;
    }

    /**
     * @return AggregateId
     */
    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    /**
     * @return AttributeId
     */
    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }
}
