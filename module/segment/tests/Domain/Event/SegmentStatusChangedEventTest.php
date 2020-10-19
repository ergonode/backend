<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Event\SegmentStatusChangedEvent;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class SegmentStatusChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var SegmentId|MockObject $id */
        $id = $this->createMock(SegmentId::class);
        /** @var SegmentStatus $from */
        $from = $this->createMock(SegmentStatus::class);
        /** @var SegmentStatus $to */
        $to = $this->createMock(SegmentStatus::class);

        $event = new SegmentStatusChangedEvent($id, $from, $to);
        self::assertSame($id, $event->getAggregateId());
        self::assertSame($from, $event->getFrom());
        self::assertSame($to, $event->getTo());
    }
}
