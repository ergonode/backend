<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
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
        /** @var StatusId $status */
        $status = $this->createMock(StatusId::class);

        $command = new UpdateWorkflowCommand($id, [$status]);
        $this->assertSame([$status], $command->getStatuses());
        $this->assertSame($id, $command->getId());
    }
}
