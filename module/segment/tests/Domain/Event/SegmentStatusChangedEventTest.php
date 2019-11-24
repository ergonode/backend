<?php

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Domain\Event;

use Ergonode\Segment\Domain\Event\SegmentStatusChangedEvent;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use PHPUnit\Framework\TestCase;

/**
 */
class SegmentStatusChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var SegmentStatus $from */
        $from = $this->createMock(SegmentStatus::class);
        /** @var SegmentStatus $to */
        $to = $this->createMock(SegmentStatus::class);

        $event = new SegmentStatusChangedEvent($from, $to);
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
    }
}
