<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SegmentCreatedEventTest extends TestCase
{
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

        $event = new SegmentCreatedEvent($id, $code, $name, $description, $conditionSetId);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($code, $event->getCode());
        $this->assertSame($name, $event->getName());
        $this->assertSame($description, $event->getDescription());
        $this->assertSame($conditionSetId, $event->getConditionSetId());
    }
}
