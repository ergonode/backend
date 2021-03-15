<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Command;

use Ergonode\Condition\Domain\Command\DeleteConditionSetCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteConditionSetCommandTest extends TestCase
{
    public function testUpdateSetCommand(): void
    {
        /** @var ConditionSetId|MockObject $id */
        $id = $this->createMock(ConditionSetId::class);
        $command = new DeleteConditionSetCommand($id);

        $this->assertSame($id, $command->getId());
    }
}
