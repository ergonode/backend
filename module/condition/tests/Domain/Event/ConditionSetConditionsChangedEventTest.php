<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Event;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Condition\Domain\Event\ConditionSetConditionsChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConditionSetConditionsChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var ConditionSetId | MockObject $id */
        $id = $this->createMock(ConditionSetId::class);
        $to = [$this->createMock(ConditionInterface::class)];

        $event = new ConditionSetConditionsChangedEvent($id, $to);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($to, $event->getTo());
    }
}
