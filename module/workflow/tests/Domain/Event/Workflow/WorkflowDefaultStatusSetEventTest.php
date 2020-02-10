<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowDefaultStatusSetEvent;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class WorkflowDefaultStatusSetEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var WorkflowId | MockObject $id */
        $id = $this->createMock(WorkflowId::class);

        /** @var StatusCode | MockObject $code */
        $code = $this->createMock(StatusCode::class);

        $event = new WorkflowDefaultStatusSetEvent($id, $code);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($code, $event->getCode());
    }
}
