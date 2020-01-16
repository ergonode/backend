<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Workflow;

use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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

        /** @var StatusCode |MockObject $source */
        $source = $this->createMock(StatusCode::class);

        /** @var StatusCode | MockObject $destination */
        $destination = $this->createMock(StatusCode::class);

        $event = new WorkflowTransitionRemovedEvent($id, $source, $destination);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($source, $event->getSource());
        $this->assertSame($destination, $event->getDestination());
    }
}
