<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

class ValueAddedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type(" Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private CategoryId $id;

    /**
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private AttributeCode $code;

    /**
     * @JMS\Type("Ergonode\Value\Domain\ValueObject\ValueInterface")
     */
    private ValueInterface $value;

    public function __construct(CategoryId $id, AttributeCode $code, ValueInterface $value)
    {
        $this->id = $id;
        $this->code = $code;
        $this->value = $value;
    }

    /**
     * @return AggregateId|CategoryId
     */
    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }


    public function getAttributeCode(): AttributeCode
    {
        return $this->code;
    }

    public function getValue(): ValueInterface
    {
        return $this->value;
    }
}
