<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Condition\Domain\Event\ConditionSetDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConditionSetDeletedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ConditionSetId | MockObject $id */
        $id = $this->createMock(ConditionSetId::class);

        $event = new ConditionSetDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
