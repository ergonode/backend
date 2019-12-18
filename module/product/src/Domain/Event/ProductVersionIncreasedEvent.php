<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductVersionIncreasedEvent implements DomainEventInterface
{
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
     * @param int $from
     * @param int $to
     */
    public function __construct(int $from, int $to)
    {
        $this->from = $from;
        $this->to = $to;
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
