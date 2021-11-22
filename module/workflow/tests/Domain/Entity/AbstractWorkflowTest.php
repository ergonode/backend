<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ramsey\Uuid\Uuid;

class AbstractWorkflowTest extends TestCase
{
    /**
     * @var WorkflowId|MockObject
     */
    private $id;

    private string $code;

    /**
     * @var StatusId|MockObject
     */
    private $status;

    protected function setUp(): void
    {
        $this->id = $this->createMock(WorkflowId::class);
        $this->code = 'Any code';
        $this->status = $this->createMock(StatusId::class);
    }

    /**
     * @throws \Exception
     */
    public function testWorkflowCreation(): void
    {
        $workflow = $this->getClass($this->id, $this->code, [$this->status]);
        $this->assertSame($this->id, $workflow->getId());
        $this->assertSame($this->code, $workflow->getCode());
        $this->assertSame([$this->status], $workflow->getStatuses());
    }

    /**
     * @throws \Exception
     */
    public function testStatusManipulation(): void
    {
        $workflow = $this->getClass($this->id, $this->code, [$this->status]);
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
        $status1 = StatusId::generate();
        $status2 = StatusId::generate();

        $workflow = $this->getClass($this->id, $this->code, [$status1, $status2]);
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
     */
    public function testSetNotExistDefaultStatus(): void
    {
        $this->expectException(\RuntimeException::class);
        /** @var StatusCode|MockObject $status */
        $status1 = StatusId::generate();
        $status2 = StatusId::generate();

        $workflow = $this->getClass($this->id, $this->code, [$status1]);
        $workflow->setDefaultStatus($status2);
    }

    /**
     * @throws \Exception
     */
    public function testGetNotExistDefaultStatus(): void
    {
        $this->expectException(\RuntimeException::class);
        $workflow = $this->getClass($this->id, $this->code, []);
        $workflow->getDefaultStatus();
    }

    /**
     * @throws \Exception
     */
    public function testNoTransitionException(): void
    {
        $this->expectException(\RuntimeException::class);

        $workflow = $this->getClass($this->id, $this->code, [$this->status]);
        $workflow->getTransition(StatusId::generate(), StatusId::generate());
    }

    /**
     * @throws \Exception
     */
    public function testAddingStatusAlreadyExistException(): void
    {
        $this->expectException(\RuntimeException::class);
        $status = StatusId::generate();
        $workflow = $this->getClass($this->id, $this->code, [$status]);
        $workflow->addStatus($status);
    }

    /**
     * @throws \Exception
     */
    public function testAddingTransitionAlreadyExistException(): void
    {
        $this->expectException(\RuntimeException::class);
        $from = StatusId::generate();
        $to = StatusId::generate();

        $workflow = $this->getClass($this->id, $this->code, [$from, $to]);
        $workflow->addTransition($from, $to);
        $workflow->addTransition($from, $to);
    }

    /**
     * @throws \Exception
     */
    public function testAddingNoFromException(): void
    {
        $this->expectException(\RuntimeException::class);
        $from = StatusId::generate();
        $to = StatusId::generate();

        $workflow = $this->getClass($this->id, $this->code, [$this->status]);
        $workflow->addTransition($from, $to);
    }

    /**
     * @throws \Exception
     */
    public function testAddingNoToException(): void
    {
        $this->expectException(\RuntimeException::class);
        $from = StatusId::generate();
        $to = StatusId::generate();
        $workflow = $this->getClass($this->id, $this->code, [$from]);
        $workflow->addTransition($from, $to);
    }

    public function testShouldSortTransitionStatuses(): void
    {
        $workflow = $this->getClass(
            $this->id,
            '0',
            [
                $id1 = new StatusId((string) Uuid::uuid4()),
                $id2 = new StatusId((string) Uuid::uuid4()),
                $id3 = new StatusId((string) Uuid::uuid4()),
                $id4 = new StatusId((string) Uuid::uuid4()),
                $id5 = new StatusId((string) Uuid::uuid4()),
                $id6 = new StatusId((string) Uuid::uuid4()),
                $id7 = new StatusId((string) Uuid::uuid4()),
                $id8 = new StatusId((string) Uuid::uuid4()),
            ],
        );
        $workflow->setDefaultStatus($id1);
        $workflow->addTransition($id3, $id4);
        $workflow->addTransition($id1, $id2);
        $workflow->addTransition($id7, $id8);
        $workflow->addTransition($id4, $id1);
        $workflow->addTransition($id2, $id3);

        $sorted = $workflow->getSortedTransitionStatuses();

        $this->assertEquals(
            [
                $id1,
                $id2,
                $id3,
                $id4,
            ],
            $sorted,
        );
    }

    /**
     * @param array $statuses
     */
    private function getClass(WorkflowId $id, string $code, array $statuses): AbstractWorkflow
    {
        return new class(
            $id,
            $code,
            $statuses
        ) extends AbstractWorkflow {
            public static function getType(): string
            {
                return 'TYPE';
            }
        };
    }
}
