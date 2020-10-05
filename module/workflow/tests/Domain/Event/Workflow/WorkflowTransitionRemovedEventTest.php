<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

/**
 */
class WorkflowTransitionRemovedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var WorkflowId |MockObject $id */
        $id = $this->createMock(WorkflowId::class);

        /** @var StatusId |MockObject $source */
        $source = $this->createMock(StatusId::class);

        /** @var StatusId | MockObject $destination */
        $destination = $this->createMock(StatusId::class);

        $event = new WorkflowTransitionRemovedEvent($id, $source, $destination);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($source, $event->getSource());
        $this->assertSame($destination, $event->getDestination());
    }
}
