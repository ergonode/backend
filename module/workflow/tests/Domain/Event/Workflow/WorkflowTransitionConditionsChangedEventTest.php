<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Event\Workflow;

use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionConditionsChangedEvent;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;

class WorkflowTransitionConditionsChangedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        $id = $this->createMock(WorkflowId::class);
        $from = $this->createMock(StatusId::class);
        $to = $this->createMock(StatusId::class);
        $conditions = [$this->createMock(WorkflowConditionInterface::class)];

        $event = new WorkflowTransitionConditionsChangedEvent($id, $from, $to, $conditions);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($from, $event->getFrom());
        $this->assertSame($to, $event->getTo());
        $this->assertSame($conditions, $event->getConditions());
    }
}
