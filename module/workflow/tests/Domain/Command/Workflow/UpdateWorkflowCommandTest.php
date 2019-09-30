<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateWorkflowCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        /** @var WorkflowId $id */
        $id = $this->createMock(WorkflowId::class);
        /** @var StatusCode $status */
        $status = $this->createMock(StatusCode::class);
        /** @var Transition $transition */
        $transition = $this->createMock(Transition::class);

        $command = new UpdateWorkflowCommand($id, [$status], [$transition]);
        $this->assertSame([$status], $command->getStatuses());
        $this->assertSame($id, $command->getId());
        $this->assertSame([$transition], $command->getTransitions());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIncorrectStatusCode(): void
    {
        /** @var WorkflowId $id */
        $id = $this->createMock(WorkflowId::class);

        new UpdateWorkflowCommand($id, [new \stdClass()]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIncorrectTransition(): void
    {
        /** @var WorkflowId $id */
        $id = $this->createMock(WorkflowId::class);

        new UpdateWorkflowCommand($id, [], [new \stdClass()]);
    }
}
