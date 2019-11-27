<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\Command\Workflow\AddWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\TestCase;

/**
 */
class AddWorkflowTransitionCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        $workflowId = $this->createMock(WorkflowId::class);
        $source = $this->createMock(StatusCode::class);
        $destination = $this->createMock(StatusCode::class);
        $name = $this->createMock(TranslatableString::class);
        $description = $this->createMock(TranslatableString::class);

        $command = new AddWorkflowTransitionCommand($workflowId, $source, $destination, $name, $description);
        $this->assertSame($workflowId, $command->getWorkflowId());
    }
}
