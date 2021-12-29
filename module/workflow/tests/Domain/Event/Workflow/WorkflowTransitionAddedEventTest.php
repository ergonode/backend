<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Event\Workflow;

use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WorkflowTransitionAddedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var WorkflowId |MockObject $id */
        $id = $this->createMock(WorkflowId::class);

        /** @var Transition |MockObject $transition */
        $transition = $this->createMock(Transition::class);


        $event = new WorkflowTransitionAddedEvent($id, $transition);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($transition, $event->getTransition());
    }
}
