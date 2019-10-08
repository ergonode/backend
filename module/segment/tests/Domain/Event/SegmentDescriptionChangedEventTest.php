<?php

namespace Ergonode\Segment\Tests\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Segment\Domain\Event\SegmentDescriptionChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class SegmentDescriptionChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var TranslatableString $from */
        $from = $this->createMock(TranslatableString::class);
        /** @var TranslatableString $to */
        $to = $this->createMock(TranslatableString::class);

        $event = new SegmentDescriptionChangedEvent($from, $to);
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
    }
}
