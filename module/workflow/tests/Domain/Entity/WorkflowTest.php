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
        $this->assertSame([$transition], $workflow->getTransitions());
        $this->assertTrue($workflow->hasTransition($source, $destination));
        $workflow->removeTransition($source, $destination);
        $this->assertFalse($workflow->hasTransition($source, $destination));
        $this->assertEmpty($workflow->getTransitions());
    }
}
