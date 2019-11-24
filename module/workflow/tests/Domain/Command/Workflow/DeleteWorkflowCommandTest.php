<?php

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowCommand;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use PHPUnit\Framework\TestCase;

/**
 */
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
