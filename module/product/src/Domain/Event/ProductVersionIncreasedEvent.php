<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Product\Domain\Entity\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductVersionIncreasedEvent implements DomainEventInterface
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductId")
     */
    private $id;

    /**
     * @var int
     *
     * @JMS\Type("integer")
     */
    private $from;

    /**
     * @var int
     *
     * @JMS\Type("integer")
     */
    private $to;

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
    public function getAggregateId(): AbstractId
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
