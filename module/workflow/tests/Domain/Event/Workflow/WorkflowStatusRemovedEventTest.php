<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Workflow;

use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusRemovedEvent;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class WorkflowStatusRemovedEventTest extends TestCase
{
    /**
     */
    public function testeEventCreation(): void
    {
        /** @var WorkflowId | MockObject $id */
        $id = $this->createMock(WorkflowId::class);

        /** @var StatusCode | MockObject $code */
        $code = $this->createMock(StatusCode::class);

        $event = new WorkflowStatusRemovedEvent($id, $code);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($code, $event->getCode());
    }
}
