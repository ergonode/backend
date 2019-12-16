<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\Workflow\Domain\Entity\TransitionId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\TestCase;

/**
 */
class TransitionTest extends TestCase
{
    /**
     * @var TransitionId
     */
    private $id;

    /**
     * @var StatusCode
     */
    private $from;

    /**
     * @var StatusCode;
     */
    private $to;

    /**
     * @var ConditionSetId
     */
    private $conditionSetId;

    /**
     * @var RoleId[]
     */
    private $roleIds;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(TransitionId::class);
        $this->from = $this->createMock(StatusCode::class);
        $this->to = $this->createMock(StatusCode::class);
        $this->roleIds = [$this->createMock(RoleId::class), $this->createMock(RoleId::class)];
        $this->conditionSetId = $this->createMock(ConditionSetId::class);
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
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds);
        $this->assertEmpty($transition->changeConditionSetId());
    }

    /**
     */
    public function testChangingConditiondSetForTheSame(): void
    {
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        $conditionSetId = $this->createMock(ConditionSetId::class);
        $conditionSetId->method('isEqual')->willReturn(true);
        $this->assertEmpty($transition->changeConditionSetId($conditionSetId));
    }

    /**
     */
    public function testChangingConditiondSet(): void
    {
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        $transition->setAggregateRoot($this->createMock(AbstractAggregateRoot::class));
        $this->assertEmpty($transition->changeConditionSetId());
    }

    /**
     */
    public function testChangingRoleIds(): void
    {
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        $transition->setAggregateRoot($this->createMock(AbstractAggregateRoot::class));
        $this->assertEmpty($transition->changeRoleIds($this->roleIds));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testChangingRoleIdsException(): void
    {
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditionSetId);
        $transition->setAggregateRoot($this->createMock(AbstractAggregateRoot::class));
        $transition->changeRoleIds(['example', 'example2']);
    }
}
