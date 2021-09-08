<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SegmentConditionSetChangedEventTest extends TestCase
{
    /**
     * @var SegmentId | MockObject $id
     */
    protected $id;

    protected function setUp(): void
    {
        $this->id = $this->createMock(SegmentId::class);
    }

    public function testEventCreation(): void
    {
        /** @var ConditionSetId | MockObject $to */
        $to = $this->createMock(ConditionSetId::class);

        $event = new SegmentConditionSetChangedEvent($this->id, $to);

        $this->assertSame($this->id, $event->getAggregateId());
        $this->assertSame($to, $event->getTo());
    }
}
