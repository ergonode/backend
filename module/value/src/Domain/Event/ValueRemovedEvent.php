<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainAggregateEventInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ValueRemovedEvent implements DomainAggregateEventInterface
{
    /**
     * @var CategoryId
     *
     * @JMS\Type(" Ergonode\Category\Domain\Entity\CategoryId")
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
     * @param CategoryId     $id
     * @param AttributeCode  $code
     * @param ValueInterface $old
     */
    public function __construct(CategoryId $id, AttributeCode $code, ValueInterface $old)
    {
        $this->id = $id;
        $this->code = $code;
        $this->old = $old;
    }

    /**
     * @return AbstractId
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
