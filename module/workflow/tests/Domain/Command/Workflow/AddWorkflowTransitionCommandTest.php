<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\Command\Workflow\AddWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;
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
        /** @var WorkflowId $workflowId */
        $workflowId = $this->createMock(WorkflowId::class);

        /** @var StatusCode | MockObject $source */
        $source = $this->createMock(StatusCode::class);

        /** @var StatusCode | MockObject $destination */
        $destination = $this->createMock(StatusCode::class);

        /** @var TranslatableString | MockObject $name */
        $name = $this->createMock(TranslatableString::class);

        /** @var TranslatableString | MockObject $description */
        $description = $this->createMock(TranslatableString::class);

        /** @var RoleId[] | MockObject[] $roleIds */
        $roleIds = [$this->createMock(RoleId::class)];

        /** @var ConditionSetId | MockObject $conditionSetId */
        $conditionSetId = $this->createMock(ConditionSetId::class);

        $command = new AddWorkflowTransitionCommand($workflowId, $source, $destination, $name, $description, $roleIds, $conditionSetId);

        $this->assertSame($workflowId, $command->getWorkflowId());
        $this->assertSame($source, $command->getSource());
        $this->assertSame($destination, $command->getDestination());
        $this->assertSame($name, $command->getName());
        $this->assertSame($description, $command->getDescription());
        $this->assertSame($roleIds, $command->getRoleIds());
        $this->assertSame($conditionSetId, $command->getConditionSetId());
    }
}
