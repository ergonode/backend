<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class SegmentConditionSetChangedEventTest extends TestCase
{
    /**
     * @var SegmentId | MockObject $id
     */
    protected $id;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(SegmentId::class);
    }

    /**
     */
    public function testEventCreation(): void
    {
        /** @var ConditionSetId | MockObject $from */
        $from = $this->createMock(ConditionSetId::class);

        /** @var ConditionSetId | MockObject $to */
        $to = $this->createMock(ConditionSetId::class);

        $event = new SegmentConditionSetChangedEvent($this->id, $from, $to);

        $this->assertSame($this->id, $event->getAggregateId());
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
    }

    /**
     */
    public function testNullException(): void
    {
        $this->expectException(\Zend\EventManager\Exception\DomainException::class);
        $from = null;
        $to = null;
        $event = new SegmentConditionSetChangedEvent($this->id, $from, $to);
    }
}
