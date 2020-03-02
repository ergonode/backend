<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductValueChangedEvent implements DomainEventInterface
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private AttributeCode $code;

    /**
     * @var ValueInterface
     *
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\ValueInterface")
     */
    private ValueInterface $from;

    /**
     * @var ValueInterface
     *
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\ValueInterface")
     */
    private ValueInterface $to;

    /**
     * @param ProductId      $id
     * @param AttributeCode  $code
     * @param ValueInterface $from
     * @param ValueInterface $to
     */
    public function __construct(ProductId $id, AttributeCode $code, ValueInterface $from, ValueInterface $to)
    {
        $this->id = $id;
        $this->code = $code;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return ProductId
     */
    public function getAggregateId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return AttributeCode
     */
    public function getAttributeCode(): AttributeCode
    {
        return $this->code;
    }

    /**
     * @return ValueInterface
     */
    public function getFrom(): ValueInterface
    {
        return $this->from;
    }

    /**
     * @return ValueInterface
     */
    public function getTo(): ValueInterface
    {
        return $this->to;
    }
}
