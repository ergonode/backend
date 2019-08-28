<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Product\Domain\ValueObject\ProductStatus;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductStatusChanged implements DomainEventInterface
{
    /**
     * @var ProductStatus
     *
     * @JMS\Type("Ergonode\Product\Domain\ValueObject\ProductStatus")
     */
    private $from;

    /**
     * @var ProductStatus
     *
     * @JMS\Type("Ergonode\Product\Domain\ValueObject\ProductStatus")
     */
    private $to;

    /**
     * @param ProductStatus $from
     * @param ProductStatus $to
     */
    public function __construct(ProductStatus $from, ProductStatus $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return ProductStatus
     */
    public function getFrom(): ProductStatus
    {
        return $this->from;
    }

    /**
     * @return ProductStatus
     */
    public function getTo(): ProductStatus
    {
        return $this->to;
    }
}
