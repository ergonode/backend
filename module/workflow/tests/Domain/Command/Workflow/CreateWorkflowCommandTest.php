<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommand;
use Ergonode\Workflow\Domain\ValueObject\Status;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateWorkflowCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        $code = 'Any code';
        /** @var Status $status */
        $status = $this->createMock(Status::class);

        $command = new CreateWorkflowCommand($code, [$status]);
        $this->assertSame($code, $command->getCode());
        $this->assertSame([$status], $command->getStatuses());
        $this->assertNotNull($command->getId());
    }
}
