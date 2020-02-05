<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Command;

use Ergonode\Segment\Domain\Entity\SegmentId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class CalculateSegmentCommand implements DomainCommandInterface
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\Segment\Domain\Entity\SegmentId")
     */
    private SegmentId $segmentId;

    /**
     * @param SegmentId $segmentId
     */
    public function __construct(SegmentId $segmentId)
    {
        $this->segmentId = $segmentId;
    }

    /**
     * @return SegmentId
     */
    public function getSegmentId(): SegmentId
    {
        return $this->segmentId;
    }
}
