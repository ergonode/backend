<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductValueRemovedEvent implements DomainEventInterface
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductId")
     */
    private $id;

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $code;

    /**
     * @var ValueInterface
     *
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\ValueInterface")
     */
    private $old;

    /**
     * @param ProductId      $id
     * @param AttributeCode  $code
     * @param ValueInterface $old
     */
    public function __construct(ProductId $id, AttributeCode $code, ValueInterface $old)
    {
        $this->id = $id;
        $this->code = $code;
        $this->old = $old;
    }

    /**
     * @return ProductId
     */
    public function getAggregateId(): AbstractId
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
    public function getOld(): ValueInterface
    {
        return $this->old;
    }
}
