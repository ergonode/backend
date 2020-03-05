<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductVersionIncreasedEvent implements DomainEventInterface
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var int
     *
     * @JMS\Type("integer")
     */
    private int $from;

    /**
     * @var int
     *
     * @JMS\Type("integer")
     */
    private int $to;

    /**
     * @param ProductId $id
     * @param int       $from
     * @param int       $to
     */
    public function __construct(ProductId $id, int $from, int $to)
    {
        $this->id = $id;
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
     * @return int
     */
    public function getFrom(): int
    {
        return $this->from;
    }

    /**
     * @return int
     */
    public function getTo(): int
    {
        return $this->to;
    }
}
