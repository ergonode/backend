<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductDraftValueChanged implements DomainEventInterface
{
    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $attributeCode;

    /**
     * @var ValueInterface
     *
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\AbstractValue")
     */
    private $from;

    /**
     * @var ValueInterface
     *
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\AbstractValue")
     */
    private $to;

    /**
     * @param AttributeCode  $attributeCode
     * @param ValueInterface $from
     * @param ValueInterface $to
     */
    public function __construct(AttributeCode $attributeCode, ValueInterface $from, ValueInterface $to)
    {
        $this->attributeCode = $attributeCode;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return AttributeCode
     */
    public function getAttributeCode(): AttributeCode
    {
        return $this->attributeCode;
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
