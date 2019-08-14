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
class ProductDraftValueRemoved implements DomainEventInterface
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
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\ValueInterface")
     */
    private $old;

    /**
     * @param AttributeCode  $attributeCode
     * @param ValueInterface $old
     */
    public function __construct(AttributeCode $attributeCode, ValueInterface $old)
    {
        $this->attributeCode = $attributeCode;
        $this->old = $old;
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
    public function getOld(): ValueInterface
    {
        return $this->old;
    }
}
