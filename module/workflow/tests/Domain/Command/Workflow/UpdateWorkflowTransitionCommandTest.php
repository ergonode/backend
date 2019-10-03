<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateWorkflowTransitionCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        /** @var WorkflowId $workflowId */
        $workflowId = $this->createMock(WorkflowId::class);
        /** @var Transition $transition */
        $transition = $this->createMock(Transition::class);

        $command = new UpdateWorkflowTransitionCommand($workflowId, $transition);
        $this->assertSame($workflowId, $command->getWorkflowId());
        $this->assertSame($transition, $command->getTransition());
    }
}
