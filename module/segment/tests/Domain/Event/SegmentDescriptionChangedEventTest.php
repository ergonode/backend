<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Segment\Tests\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Segment\Domain\Entity\SegmentId;
use Ergonode\Segment\Domain\Event\SegmentDescriptionChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class SegmentDescriptionChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var SegmentId|MockObject $id */
        $id = $this->createMock(SegmentId::class);
        /** @var TranslatableString $from */
        $from = $this->createMock(TranslatableString::class);
        /** @var TranslatableString $to */
        $to = $this->createMock(TranslatableString::class);

        $event = new SegmentDescriptionChangedEvent($id, $from, $to);
        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
    }
}
