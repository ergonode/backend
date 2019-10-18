<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class WorkflowTest extends TestCase
{
    /**
     * @var WorkflowId|MockObject
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var StatusCode|MockObject
     */
    private $status;

    /**
     */
    protected function setUp()
    {
        $this->id = $this->createMock(WorkflowId::class);
        $this->code = 'Any code';
        $this->status = $this->createMock(StatusCode::class);
    }

    /**
     * @throws \Exception
     */
    public function testWorkflowCreation(): void
    {
        $workflow = new Workflow($this->id, $this->code, [$this->status]);
        $this->assertSame($this->id, $workflow->getId());
        $this->assertSame($this->code, $workflow->getCode());
        $this->assertSame([$this->status], $workflow->getStatuses());
    }

    /**
     * @throws \Exception
     */
    public function testStatusManipulation(): void
    {
        /** @var StatusCode|MockObject $status */
        $status = $this->createMock(StatusCode::class);

        $workflow = new Workflow($this->id, $this->code, [$this->status]);
        $this->assertSame($this->id, $workflow->getId());
        $this->assertSame($this->code, $workflow->getCode());
        $this->assertSame([$this->status], $workflow->getStatuses());
        $this->assertTrue($workflow->hasStatus($this->status));
        $workflow->removeStatus($this->status);
        $this->assertFalse($workflow->hasStatus($this->status));
        $this->assertEmpty($workflow->getStatuses());
        $workflow->addStatus($this->status);
        $this->assertTrue($workflow->hasStatus($this->status));
        $this->assertSame([$this->status], $workflow->getStatuses());
    }

    /**
     * @throws \Exception
     */
    public function testDefaultStatusManipulation(): void
    {
        /** @var StatusCode|MockObject $status */
        $status1 = new StatusCode('one');
        $status2 = new StatusCode('two');

        $workflow = new Workflow($this->id, $this->code, [$status1, $status2]);
        $this->assertTrue($workflow->hasDefaultStatus());
        $this->assertSame($status1, $workflow->getDefaultStatus());
        $workflow->setDefaultStatus($status2);
        $this->assertTrue($workflow->hasDefaultStatus());
        $this->assertSame($status2, $workflow->getDefaultStatus());
        $workflow->removeStatus($status2);
        $this->assertTrue($workflow->hasDefaultStatus());
        $this->assertSame($status1, $workflow->getDefaultStatus());
        $workflow->removeStatus($status1);
        $this->assertFalse($workflow->hasDefaultStatus());
    }

    /**
     * @throws \Exception
     *
     * @expectedException \RuntimeException
     */
    public function testSetNotExistDefaultStatus(): void
    {
        /** @var StatusCode|MockObject $status */
        $status1 = new StatusCode('one');
        $status2 = new StatusCode('two');

        $workflow = new Workflow($this->id, $this->code, [$status1]);
        $workflow->setDefaultStatus($status2);
    }

    /**
     * @throws \Exception
     *
     * @expectedException \RuntimeException
     */
    public function testGetNotExistDefaultStatus(): void
    {
        $workflow = new Workflow($this->id, $this->code, []);
        $workflow->getDefaultStatus();
    }

    /**
     * @throws \Exception
     */
    public function testTransitionManipulation(): void
    {
        /** @var Transition|MockObject $transition */
        $transition = $this->createMock(Transition::class);
        /** @var StatusCode|MockObject $source */
        $source = new StatusCode('A');
        $destination = new StatusCode('B');
        $transition->method('getSource')->willReturn($source);
        $transition->method('getDestination')->willReturn($destination);

        $workflow = new Workflow($this->id, $this->code, [$source, $destination]);
        $workflow->addTransition($transition);

        $this->assertSame($transition, $workflow->getTransition($source, $destination));
        $this->assertSame([$transition], $workflow->getTransitions());
        $this->assertTrue($workflow->hasTransition($source, $destination));
        $result = $workflow->getTransitionsFromStatus($source);
        $this->assertSame([$transition], $workflow->getTransitionsFromStatus($source));
        $workflow->changeTransition($source, $destination, $transition);
        $workflow->removeTransition($source, $destination);
        $this->assertFalse($workflow->hasTransition($source, $destination));
        $this->assertEmpty($workflow->getTransitions());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Transition from "A" to "B" not exists
     */
    public function testNoTransitionException(): void
    {

        $workflow = new Workflow($this->id, $this->code, [$this->status]);
        $workflow->getTransition(new StatusCode('A'), new StatusCode('B'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Status "A" already exists
     */
    public function testAddingStatusAlreadyExistException(): void
    {
        $status = new StatusCode('A');
        $workflow = new Workflow($this->id, $this->code, [$status]);
        $workflow->addStatus($status);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Transition from "A" to "B" already exists
     */
    public function testAddingTransitionAlreadyExistException()
    {
        /** @var Transition|MockObject $transition1 */
        $transition1 = $this->createMock(Transition::class);
        /** @var StatusCode|MockObject $source */
        $source = new StatusCode('A');
        $destination = new StatusCode('B');
        $transition1->method('getSource')->willReturn($source);
        $transition1->method('getDestination')->willReturn($destination);

        /** @var Transition|MockObject $transition2 */
        $transition2 = $this->createMock(Transition::class);
        $transition2->method('getSource')->willReturn($source);
        $transition2->method('getDestination')->willReturn($destination);

        $workflow = new Workflow($this->id, $this->code, [$source, $destination]);
        $workflow->addTransition($transition1);
        $workflow->addTransition($transition2);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Transition source status "A" not exists
     */
    public function testAddingNoSourceException()
    {
        /** @var Transition|MockObject $transition1 */
        $transition = $this->createMock(Transition::class);
        /** @var StatusCode|MockObject $source */
        $source = new StatusCode('A');
        $destination = new StatusCode('B');
        $transition->method('getSource')->willReturn($source);
        $transition->method('getDestination')->willReturn($destination);
        $workflow = new Workflow($this->id, $this->code, [$this->status]);
        $workflow->addTransition($transition);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Transition destination status "B" not exists
     */
    public function testAddingNoDestinationException()
    {
        /** @var Transition|MockObject $transition1 */
        $transition = $this->createMock(Transition::class);
        /** @var StatusCode|MockObject $source */
        $source = new StatusCode('A');
        $destination = new StatusCode('B');
        $transition->method('getSource')->willReturn($source);
        $transition->method('getDestination')->willReturn($destination);
        $workflow = new Workflow($this->id, $this->code, [$source]);
        $workflow->addTransition($transition);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Transition not exists
     */
    public function testChangingTransitionNotExistsException()
    {
        /** @var Transition|MockObject $transition */
        $transition = $this->createMock(Transition::class);
        $source = new StatusCode('A');
        $destination = new StatusCode('B');
        $workflow = new Workflow($this->id, $this->code, [$this->status]);
        $workflow->changeTransition($source, $destination, $transition);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Transition source status "A" not exists
     */
    public function testChangingSourceNotExistsException()
    {
        /** @var Transition|MockObject $transition */
        $transition = $this->createMock(Transition::class);
        $source = new StatusCode('A');
        $destination = new StatusCode('B');
        $transition->method('getSource')->willReturn($source);
        $transition->method('getDestination')->willReturn($destination);
        $workflow = new Workflow($this->id, $this->code, [$source, $destination]);
        $workflow->addTransition($transition);
        $workflow->removeStatus($source);
        $workflow->changeTransition($source, $destination, $transition);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Transition destination status "B" not exists
     */
    public function testChangingDestinationNotExistsException()
    {
        /** @var Transition|MockObject $transition */
        $transition = $this->createMock(Transition::class);
        $source = new StatusCode('A');
        $destination = new StatusCode('B');
        $transition->method('getSource')->willReturn($source);
        $transition->method('getDestination')->willReturn($destination);
        $workflow = new Workflow($this->id, $this->code, [$source, $destination]);
        $workflow->addTransition($transition);
        $workflow->removeStatus($destination);
        $workflow->changeTransition($source, $destination, $transition);
    }
}
