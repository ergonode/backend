<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\TestCase;

/**
 */
class TransitionTest extends TestCase
{
    /**
     * @var TransitionId
     */
    private TransitionId $id;

    /**
     * @var StatusCode
     */
    private StatusCode $from;

    /**
     * @var StatusCode
     */
    private StatusCode $to;

    /**
     * @var ConditionSetId
     */
    private ConditionSetId $conditionSetId;

    /**
     * @var RoleId[]
     */
    private array $roleIds;

    /**
     * @var AbstractAggregateRoot
     */
    private AbstractAggregateRoot $aggregateRoot;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(TransitionId::class);
        $this->id->method('isEqual')->willReturn(true);
        $this->from = $this->createMock(StatusCode::class);
        $this->to = $this->createMock(StatusCode::class);
        $this->roleIds = [$this->createMock(RoleId::class), $this->createMock(RoleId::class)];
        $this->conditionSetId = $this->createMock(ConditionSetId::class);
        $this->aggregateRoot = $this->createMock(AbstractAggregateRoot::class);
        $this->aggregateRoot->method('getId')->willReturn($this->createMock(WorkflowId::class));
    }

    /**
     */
    public function testTransitionCreation(): void
    {
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        $this->assertSame($this->id, $transition->getId());
        $this->assertSame($this->from, $transition->getFrom());
        $this->assertSame($this->to, $transition->getTo());
        $this->assertSame($this->roleIds, $transition->getRoleIds());
        $this->assertSame($this->conditionSetId, $transition->getConditionSetId());
    }

    /**
     */
    public function testChangingConditionSetNull(): void
    {
        $this->aggregateRoot->expects($this->once())->method('apply');
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        $transition->setAggregateRoot($this->aggregateRoot);
        $transition->changeConditionSetId();
    }

    /**
     */
    public function testChangingConditionSetForTheSame(): void
    {
        $this->aggregateRoot->expects($this->never())->method('apply');
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        $conditionSetId = $this->createMock(ConditionSetId::class);
        $conditionSetId->method('isEqual')->willReturn(true);
        $transition->changeConditionSetId($conditionSetId);
    }

    /**
     * @throws \Exception
     */
    public function testChangingRoleIds(): void
    {
        $this->aggregateRoot->expects($this->once())->method('apply');
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        $transition->setAggregateRoot($this->aggregateRoot);
        $transition->changeRoleIds($this->roleIds);
    }

    /**
     */
    public function testChangingRoleIdsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        $transition->setAggregateRoot($this->aggregateRoot);
        $transition->changeRoleIds(['example', 'example2']);
    }
}
