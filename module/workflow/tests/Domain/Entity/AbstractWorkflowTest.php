<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ramsey\Uuid\Uuid;

/**
 */
class AbstractWorkflowTest extends TestCase
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
     * @var StatusId|MockObject
     */
    private $status;

    /**
     */
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
     *
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
     *
     */
    public function testGetNotExistDefaultStatus(): void
    {
        $this->expectException(\RuntimeException::class);
        $workflow = $this->getClass($this->id, $this->code, []);
        $workflow->getDefaultStatus();
    }

    /**
     *
     *
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
     *
     *
     * @throws \Exception
     */
    public function testAddingTransitionAlreadyExistException(): void
    {
        $this->expectException(\RuntimeException::class);
        $source = StatusId::generate();
        $destination = StatusId::generate();

        $workflow = $this->getClass($this->id, $this->code, [$source, $destination]);
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
        $this->expectException(\RuntimeException::class);
        $source = StatusId::generate();
        $destination = StatusId::generate();

        $workflow = $this->getClass($this->id, $this->code, [$this->status]);
        $workflow->addTransition($source, $destination);
    }

    /**
     *
     *
     * @throws \Exception
     */
    public function testAddingNoDestinationException(): void
    {
        $this->expectException(\RuntimeException::class);
        $source = StatusId::generate();
        $destination = StatusId::generate();
        $workflow = $this->getClass($this->id, $this->code, [$source]);
        $workflow->addTransition($source, $destination);
    }

    /**
     * @param WorkflowId $id
     * @param string     $code
     * @param array      $statuses
     *
     * @return AbstractWorkflow
     */
    private function getClass(WorkflowId $id, string $code, array $statuses): AbstractWorkflow
    {
        return new class(
            $id,
            $code,
            $statuses
        ) extends AbstractWorkflow {
            /**
             * @return string
             */
            public static function getType(): string
            {
                return 'TYPE';
            }
        };
    }
}
