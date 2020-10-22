<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

class ProductDraftValueChanged implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId")
     */
    private ProductDraftId $id;

    /**
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private AttributeCode $attributeCode;

    /**
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\ValueInterface")
     */
    private ValueInterface $from;

    /**
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\ValueInterface")
     */
    private ValueInterface $to;

    public function __construct(
        ProductDraftId $id,
        AttributeCode $attributeCode,
        ValueInterface $from,
        ValueInterface $to
    ) {
        $this->id = $id;
        $this->attributeCode = $attributeCode;
        $this->from = $from;
        $this->to = $to;
    }

    public function getAggregateId(): ProductDraftId
    {
        return $this->id;
    }

    public function getAttributeCode(): AttributeCode
    {
        return $this->attributeCode;
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
