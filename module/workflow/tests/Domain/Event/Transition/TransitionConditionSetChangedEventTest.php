<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Transition;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Event\Transition\TransitionConditionSetChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class TransitionConditionSetChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var WorkflowId |MockObject $id */
        $id = $this->createMock(WorkflowId::class);

        /** @var TransitionId | MockObject $transitionId */
        $transitionId = $this->createMock(TransitionId::class);

        /** @var ConditionSetId | MockObject $conditionSetId */
        $conditionSetId = $this->createMock(ConditionSetId::class);

        $event = new TransitionConditionSetChangedEvent($id, $transitionId, $conditionSetId);

        self::assertSame($id, $event->getAggregateId());
        self::assertSame($transitionId, $event->getTransitionId());
        self::assertSame($conditionSetId, $event->getConditionSetId());
    }
}
