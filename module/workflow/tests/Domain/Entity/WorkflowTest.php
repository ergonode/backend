<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
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
    private string $code;

    /**
     * @var StatusCode|MockObject
     */
    private $status;

    /**
     */
    protected function setUp(): void
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
     */
    public function testSetNotExistDefaultStatus(): void
    {
        $this->expectException(\RuntimeException::class);
        /** @var StatusCode|MockObject $status */
        $status1 = new StatusCode('one');
        $status2 = new StatusCode('two');

        $workflow = new Workflow($this->id, $this->code, [$status1]);
        $workflow->setDefaultStatus($status2);
    }

    /**
     * @throws \Exception
     *
     */
    public function testGetNotExistDefaultStatus(): void
    {
        $this->expectException(\RuntimeException::class);
        $workflow = new Workflow($this->id, $this->code, []);
        $workflow->getDefaultStatus();
    }

    /**
     *
     *
     * @throws \Exception
     */
    public function testNoTransitionException(): void
    {
        $this->expectExceptionMessage('Transition from "A" to "B" not exists');
        $this->expectException(\RuntimeException::class);

        $workflow = new Workflow($this->id, $this->code, [$this->status]);
        $workflow->getTransition(new StatusCode('A'), new StatusCode('B'));
    }

    /**
     * @throws \Exception
     */
    public function testAddingStatusAlreadyExistException(): void
    {
        $this->expectExceptionMessage('Status "A" already exists');
        $this->expectException(\RuntimeException::class);
        $status = new StatusCode('A');
        $workflow = new Workflow($this->id, $this->code, [$status]);
        $workflow->addStatus($status);
    }

    /**
     *
     *
     * @throws \Exception
     */
    public function testAddingTransitionAlreadyExistException(): void
    {
        $this->expectExceptionMessage('Transition from "A" to "B" already exists');
        $this->expectException(\RuntimeException::class);
        /** @var StatusCode|MockObject $source */
        $source = new StatusCode('A');
        $destination = new StatusCode('B');


        $workflow = new Workflow($this->id, $this->code, [$source, $destination]);
        $workflow->addTransition($source, $destination);
        $workflow->addTransition($source, $destination);
    }

    /**
     *
     *
     * @throws \Exception
     */
    public function testAddingNoSourceException(): void
    {
        $this->expectExceptionMessage('Transition source status "A" not exists');
        $this->expectException(\RuntimeException::class);
        /** @var StatusCode|MockObject $source */
        $source = new StatusCode('A');
        $destination = new StatusCode('B');

        $workflow = new Workflow($this->id, $this->code, [$this->status]);
        $workflow->addTransition($source, $destination);
    }

    /**
     *
     *
     * @throws \Exception
     */
    public function testAddingNoDestinationException(): void
    {
        $this->expectExceptionMessage('Transition destination status "B" not exists');
        $this->expectException(\RuntimeException::class);
        /** @var StatusCode|MockObject $source */
        $source = new StatusCode('A');
        $destination = new StatusCode('B');
        $workflow = new Workflow($this->id, $this->code, [$source]);
        $workflow->addTransition($source, $destination);
    }
}
