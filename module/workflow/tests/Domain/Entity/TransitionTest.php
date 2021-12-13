<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class TransitionTest extends TestCase
{
    private TransitionId $id;

    private StatusId $from;

    private StatusId $to;

    private ConditionSetId $conditionSetId;

    /**
     * @var RoleId[]
     */
    private array $roleIds;

    protected function setUp(): void
    {
        $this->id = $this->createMock(TransitionId::class);
        $this->id->method('isEqual')->willReturn(true);
        $this->from = $this->createMock(StatusId::class);
        $this->to = $this->createMock(StatusId::class);
        $this->roleIds = [$this->createMock(RoleId::class), $this->createMock(RoleId::class)];
        $this->conditionSetId = $this->createMock(ConditionSetId::class);
        $this->aggregateRoot = $this->createMock(AbstractAggregateRoot::class);
        $this->aggregateRoot->method('getId')->willReturn($this->createMock(WorkflowId::class));
    }

    public function testTransitionCreation(): void
    {
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        self::assertSame($this->id, $transition->getId());
        self::assertSame($this->from, $transition->getFrom());
        self::assertSame($this->to, $transition->getTo());
        self::assertSame($this->roleIds, $transition->getRoleIds());
        self::assertContainsOnlyInstancesOf(RoleId::class, $this->roleIds);
        self::assertSame($this->conditionSetId, $transition->getConditionSetId());
    }

    public function testChangingConditionSetNull(): void
    {
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        $transition->changeConditionSetId();

        self::assertNull($transition->getConditionSetId());
    }

    public function testChangingConditionSetForTheSame(): void
    {
        $transition = new Transition($this->id, $this->from, $this->to);
        $conditionSetId = $this->createMock(ConditionSetId::class);
        $transition->changeConditionSetId($conditionSetId);

        self::assertEquals($conditionSetId, $transition->getConditionSetId());
    }

    public function testChangingRoleIds(): void
    {
        $transition = new Transition($this->id, $this->from, $this->to);
        $transition->changeRoleIds($this->roleIds);

        self::assertSame($this->roleIds, $transition->getRoleIds());
    }

    public function testChangingRoleIdsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $transition = new Transition($this->id, $this->from, $this->to);
        $transition->changeRoleIds(['example', 'example2']);
    }
}
