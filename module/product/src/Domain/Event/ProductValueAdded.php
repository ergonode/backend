<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductValueAdded implements DomainEventInterface
{
    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $code;

    /**
     * @var ValueInterface
     *
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\AbstractValue")
     */
    private $value;

    /**
     * @param AttributeCode  $code
     * @param ValueInterface $value
     */
    public function __construct(AttributeCode $code, ValueInterface $value)
    {
        $this->code = $code;
        $this->value = $value;
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
    public function getValue(): ValueInterface
    {
        return $this->value;
    }
}
