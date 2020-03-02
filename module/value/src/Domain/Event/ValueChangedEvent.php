<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ValueChangedEvent implements DomainEventInterface
{
    /**
     * @var CategoryId
     *
     * @JMS\Type(" Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private CategoryId $id;

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
     * @param CategoryId     $id
     * @param AttributeCode  $code
     * @param ValueInterface $from
     * @param ValueInterface $to
     */
    public function __construct(CategoryId $id, AttributeCode $code, ValueInterface $from, ValueInterface $to)
    {
        $this->id = $id;
        $this->code = $code;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return CategoryId|AggregateId
     */
    public function getAggregateId(): AggregateId
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
