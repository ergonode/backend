<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\Workflow\Domain\Entity\Workflow;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

class WorkflowTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testType(): void
    {
        $workflow = new Workflow(WorkflowId::generate(), 'code', []);
        $this->assertSame(Workflow::TYPE, $workflow->getType());
    }
}
