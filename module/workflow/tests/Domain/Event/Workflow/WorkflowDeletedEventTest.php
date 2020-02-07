<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowDeletedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class WorkflowDeletedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var WorkflowId | MockObject $id */
        $id = $this->createMock(WorkflowId::class);

        $event = new WorkflowDeletedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
