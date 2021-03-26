<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Event;

use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\SharedKernel\Application\ApplicationEventInterface;

class SegmentUpdatedEvent implements ApplicationEventInterface
{
    private Segment $segment;

    public function __construct(Segment $segment)
    {
        $this->segment = $segment;
    }

    public function getSegment(): Segment
    {
        return $this->segment;
    }
}
