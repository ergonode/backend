<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Event\SegmentDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SegmentDeletedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var SegmentId | MockObject $id */
        $id = $this->createMock(SegmentId::class);

        $event = new SegmentDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
