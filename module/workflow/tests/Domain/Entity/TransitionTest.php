<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;

class TransitionTest extends TestCase
{
    private TransitionId $id;

    private StatusId $from;

    private StatusId $to;

    /**
     * @var WorkflowConditionInterface[]
     */
    private array $conditions;

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
        $this->conditions = [$this->createMock(WorkflowConditionInterface::class)];
    }

    public function testTransitionCreation(): void
    {
        $transition = new Transition($this->id, $this->from, $this->to, $this->roleIds, $this->conditions);

        self::assertSame($this->id, $transition->getId());
        self::assertSame($this->from, $transition->getFrom());
        self::assertSame($this->to, $transition->getTo());
        self::assertSame($this->roleIds, $transition->getRoleIds());
        self::assertSame($this->conditions, $transition->getConditions());
    }

    public function testTransitionCreationInvalidRoleIds(): void
    {
        $this->expectException(\InvalidArgumentException::class);

         new Transition($this->id, $this->from, $this->to, [new \stdClass()], $this->conditions);
    }

    public function testTransitionCreationInvalidConditions(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Transition($this->id, $this->from, $this->to, $this->roleIds, [new \stdClass()]);
    }
}
