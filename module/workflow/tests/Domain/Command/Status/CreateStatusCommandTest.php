<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Status;

use Ergonode\Workflow\Domain\Command\Status\CreateStatusCommand;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\Status;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateStatusCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        /** @var WorkflowId $id */
        $id = $this->createMock(WorkflowId::class);
        $code = 'Any code';
        /** @var Status $status */
        $status = $this->createMock(Status::class);

        $command = new CreateStatusCommand($id, $code, $status);
        $this->assertSame($id, $command->getId());
        $this->assertSame($code, $command->getCode());
        $this->assertSame($status, $command->getStatus());
    }
}
