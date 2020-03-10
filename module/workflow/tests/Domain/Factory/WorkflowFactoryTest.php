<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Factory\WorkflowFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class WorkflowFactoryTest extends TestCase
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
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(WorkflowId::class);
        $this->code = 'Any code';
    }

    /**
     */
    public function testCreateObject(): void
    {
        $factory = new WorkflowFactory();
        $workflow  = $factory->create($this->id, $this->code);
        $this->assertNotNull($workflow);
        $this->assertSame($this->id, $workflow->getId());
        $this->assertSame($this->code, $workflow->getCode());
    }
}
