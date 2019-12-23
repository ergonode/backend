<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Transition;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Workflow\Domain\Entity\TransitionId;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Event\Transition\TransitionRoleIdsChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class TransitionRoleIdsChangedEventTest extends TestCase
{
    /**
     */
    public function testEventCreation(): void
    {
        /** @var WorkflowId|MockObject $id */
        $id = $this->createMock(WorkflowId::class);

        /** @var TransitionId | MockObject $transitionId */
        $transitionId = $this->createMock(TransitionId::class);

        /** @var RoleId[] |MockObject $roleIds */
        $roleIds = [$this->createMock(RoleId::class)];

        $event = new TransitionRoleIdsChangedEvent($id, $transitionId, $roleIds);

        $this->assertSame($id, $event->getAggregateId());
        $this->assertSame($transitionId, $event->getTransitionId());
        $this->assertSame($roleIds, $event->getRoleIds());
    }
}
