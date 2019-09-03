<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\Segment\Domain\Specification\SegmentSpecificationInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class SegmentSpecificationAddedEvent implements DomainEventInterface
{
    /**
     * @var SegmentSpecificationInterface
     *
     * @JMS\Type("Ergonode\Segment\Infrastructure\Specification\AbstractChannelSpecification")
     */
    private $specification;

    /**
     * @param SegmentSpecificationInterface $specification
     */
    public function __construct(SegmentSpecificationInterface $specification)
    {
        $this->specification = $specification;
    }

    /**
     * @return SegmentSpecificationInterface
     */
    public function getSpecification(): SegmentSpecificationInterface
    {
        return $this->specification;
    }
}
