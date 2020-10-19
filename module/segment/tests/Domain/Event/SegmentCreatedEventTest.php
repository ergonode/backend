<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class SegmentCreatedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var SegmentId|MockObject $id */
        $id = $this->createMock(SegmentId::class);
        /** @var TranslatableString $name */
        $name = $this->createMock(TranslatableString::class);
        /** @var TranslatableString $description */
        $description = $this->createMock(TranslatableString::class);
        /** @var SegmentCode|MockObject $code */
        $code = $this->createMock(SegmentCode::class);
        /** @var ConditionSetId|MockObject $conditionSetId */
        $conditionSetId = $this->createMock(ConditionSetId::class);
        /** @var SegmentStatus|MockObject $status */
        $status = $this->createMock(SegmentStatus::class);

        $event = new SegmentCreatedEvent($id, $code, $name, $description, $status, $conditionSetId);

        self::assertSame($id, $event->getAggregateId());
        self::assertSame($code, $event->getCode());
        self::assertSame($name, $event->getName());
        self::assertSame($description, $event->getDescription());
        self::assertSame($conditionSetId, $event->getConditionSetId());
        self::assertSame($status, $event->getStatus());
    }
}
