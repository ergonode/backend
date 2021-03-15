<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Domain\Command;

use Ergonode\Segment\Domain\Command\DeleteSegmentCommand;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteSegmentCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        /** @var SegmentId | MockObject $id */
        $id = $this->createMock(SegmentId::class);

        $command = new DeleteSegmentCommand($id);

        $this->assertSame($id, $command->getId());
    }
}
