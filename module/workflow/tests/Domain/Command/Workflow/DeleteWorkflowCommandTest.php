<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowCommand;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use PHPUnit\Framework\TestCase;

class DeleteWorkflowCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        /** @var WorkflowId $id */
        $id = $this->createMock(WorkflowId::class);

        $command = new DeleteWorkflowCommand($id);
        $this->assertSame($id, $command->getId());
    }
}
