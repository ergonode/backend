<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Event\Transition;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Workflow\Domain\Entity\TransitionId;
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
        /** @var TransitionId | MockObject $transitionId1 */
        $transitionId1 = $this->createMock(TransitionId::class);

        /** @var RoleId[] |MockObject $roleIds */
        $roleIds = [$this->createMock(RoleId::class)];

        /** @var TransitionId | MockObject $transitionId2 */
        $transitionId2 = $this->createMock(TransitionId::class);

        $event = new TransitionRoleIdsChangedEvent($transitionId1, $roleIds);

        $this->assertSame($transitionId1, $event->getTransitionId());
        $event->setTransitionId($transitionId2);
        $this->assertSame($transitionId2, $event->getTransitionId());
        $this->assertSame($roleIds, $event->getRoleIds());
    }
}
