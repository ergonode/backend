<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Editor\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

class ProductDraftValueRemoved implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId")
     */
    private ProductDraftId $id;

    /**
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private AttributeCode $attributeCode;

    public function __construct(ProductDraftId $id, AttributeCode $attributeCode)
    {
        $this->id = $id;
        $this->attributeCode = $attributeCode;
    }

    public function getAggregateId(): ProductDraftId
    {
        return $this->id;
    }

    public function getAttributeCode(): AttributeCode
    {
        return $this->attributeCode;
    }
}
