<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Transition;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Workflow\Domain\Entity\TransitionId;
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
        /** @var TransitionId | MockObject $transitionId */
        $transitionId = $this->createMock(TransitionId::class);

        /** @var ConditionSetId | MockObject $conditionSetId */
        $conditionSetId = $this->createMock(ConditionSetId::class);

        $event = new TransitionConditionSetChangedEvent($transitionId, $conditionSetId);

        $this->assertSame($transitionId, $event->getTransitionId());
        $this->assertSame($conditionSetId, $event->getConditionSetId());
    }
}
