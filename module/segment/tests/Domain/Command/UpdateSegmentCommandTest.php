<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Segment\Domain\Command\UpdateSegmentCommand;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateSegmentCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        /** @var SegmentId|MockObject $id */
        $id = $this->createMock(SegmentId::class);
        /** @var TranslatableString $name */
        $name = $this->createMock(TranslatableString::class);
        /** @var TranslatableString $description */
        $description = $this->createMock(TranslatableString::class);
        /** @var ConditionSetId $conditionSetId */
        $conditionSetId = $this->createMock(ConditionSetId::class);

        $command = new UpdateSegmentCommand($id, $name, $description, $conditionSetId);
        $this->assertEquals($id, $command->getId());
        $this->assertEquals($name, $command->getName());
        $this->assertEquals($description, $command->getDescription());
        $this->assertEquals($conditionSetId, $command->getConditionSetId());
    }
}
