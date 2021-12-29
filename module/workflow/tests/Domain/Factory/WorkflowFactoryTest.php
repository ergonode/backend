<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Factory\WorkflowFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class WorkflowFactoryTest extends TestCase
{
    /**
     * @var WorkflowId|MockObject
     */
    private $id;

    private string $code;

    private StatusId $statusId1;

    private StatusId $statusId2;

    protected function setUp(): void
    {
        $this->id = $this->createMock(WorkflowId::class);
        $this->code = 'Any code';
        $this->statusId1 = StatusId::generate();
        $this->statusId2 = StatusId::generate();
    }

    public function testCreateObject(): void
    {
        $factory = new WorkflowFactory();
        $workflow  = $factory->create($this->id, $this->code, [$this->statusId1, $this->statusId2], $this->statusId2);
        $this->assertNotNull($workflow);
        $this->assertSame($this->id, $workflow->getId());
        $this->assertSame($this->code, $workflow->getCode());
        $this->assertSame([$this->statusId1, $this->statusId2], $workflow->getStatuses());
        $this->assertSame($this->statusId2, $workflow->getDefaultStatus());
    }
}
