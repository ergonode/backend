<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusAddedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class WorkflowStatusAddedEventTest extends TestCase
{
    public function testeEventCreation(): void
    {
        /** @var WorkflowId | MockObject $id */
        $id = $this->createMock(WorkflowId::class);

        /** @var StatusId | MockObject $statusId */
        $statusId = $this->createMock(StatusId::class);

        $event = new WorkflowStatusAddedEvent($id, $statusId);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($statusId, $event->getStatusId());
    }
}
