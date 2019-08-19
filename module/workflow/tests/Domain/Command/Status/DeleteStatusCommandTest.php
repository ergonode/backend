<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Status;

use Ergonode\Workflow\Domain\Command\Status\DeleteStatusCommand;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteStatusCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        /** @var WorkflowId $id */
        $id = $this->createMock(WorkflowId::class);
        $code = 'Any code';

        $command = new DeleteStatusCommand($id, $code);
        $this->assertSame($id, $command->getId());
        $this->assertSame($code, $command->getCode());
    }
}
