<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

class CalculateSegmentCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $segmentId;

    public function __construct(SegmentId $segmentId)
    {
        $this->segmentId = $segmentId;
    }

    public function getSegmentId(): SegmentId
    {
        return $this->segmentId;
    }
}
