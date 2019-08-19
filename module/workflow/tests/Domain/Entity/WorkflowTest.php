<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\Status;
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
     * @var Status|MockObject
     */
    private $status;

    /**
     */
    protected function setUp()
    {
        $this->id = $this->createMock(WorkflowId::class);
        $this->code = 'Any code';
        $this->status = $this->createMock(Status::class);
    }

    /**
     * @throws \Exception
     */
    public function testWorkflowCreation(): void
    {
        $workflow = new Workflow($this->id, $this->code, [$this->code => $this->status]);
        $this->assertSame($this->id, $workflow->getId());
        $this->assertSame($this->code, $workflow->getCode());
        $this->assertSame([$this->code => $this->status], $workflow->getStatuses());
    }

    /**
     * @throws \Exception
     */
    public function testStatusManipulation(): void
    {
        /** @var Status $status */
        $status = $this->createMock(Status::class);

        $workflow = new Workflow($this->id, $this->code, [$this->code => $this->status]);
        $this->assertSame($this->id, $workflow->getId());
        $this->assertSame($this->code, $workflow->getCode());
        $this->assertSame([$this->code => $this->status], $workflow->getStatuses());
        $this->assertTrue($workflow->hasStatus($this->code));
        $workflow->removeStatus($this->code);
        $this->assertFalse($workflow->hasStatus($this->code));
        $this->assertEmpty($workflow->getStatuses());
        $workflow->addStatus($this->code, $this->status);
        $this->assertTrue($workflow->hasStatus($this->code));
        $this->assertSame([$this->code => $this->status], $workflow->getStatuses());
        $workflow->changeStatus($this->code, $status);
        $this->assertSame($status, $workflow->getStatus($this->code));
        $this->assertNotSame($this->status, $workflow->getStatus($this->code));
    }
}
